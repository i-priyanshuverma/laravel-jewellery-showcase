<?php

namespace Tests\Feature\Vendor;

use App\Enums\ProductStatus;
use App\Jobs\ProcessCsvImportChunk;
use App\Models\CsvImport;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CsvImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_vendor_can_download_sample_csv_template(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();

        $response = $this->actingAs($vendor)->get(route('vendor.imports.sample'));

        $response->assertOk();
        $response->assertHeader('Content-Disposition', 'attachment; filename="jewellery_products_sample.csv"');
    }

    public function test_vendor_can_upload_csv_import_file(): void
    {
        Storage::fake(config('filesystems.default', 'public'));

        $vendor = User::factory()->vendor()->approved()->create();

        $header = "product_name,category,description,status,is_featured,sku,price,stock,metal,purity,colour,size,weight\n";
        $row1 = "Diamond Ring,Rings,Nice ring,active,yes,SKU-DR-1,50000.00,10,Gold,18K,Yellow,6,4.5\n";

        $file = UploadedFile::fake()->createWithContent('import.csv', $header.$row1);

        $response = $this->actingAs($vendor)->post(route('vendor.imports.store'), [
            'csv_file' => $file,
        ]);

        $import = CsvImport::where('user_id', $vendor->id)->first();
        $this->assertNotNull($import);
        $this->assertEquals('import.csv', $import->filename);

        $response->assertRedirect(route('vendor.imports.show', $import));
    }

    public function test_csv_import_chunk_job_processes_valid_and_invalid_rows(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $csvImport = CsvImport::factory()->create([
            'user_id' => $vendor->id,
            'total_rows' => 2,
            'processed_rows' => 0,
            'successful_rows' => 0,
            'failed_rows' => 0,
            'status' => 'processing',
        ]);

        $chunkData = [
            [
                'product_name' => 'Gold Bangle',
                'category' => 'Bangles',
                'description' => 'Fine 22K gold bangle',
                'status' => 'active',
                'is_featured' => 'yes',
                'sku' => 'SKU-GB-1',
                'price' => 75000.00,
                'stock' => 5,
                'metal' => 'Gold',
                'purity' => '22K',
                'colour' => 'Yellow',
                'size' => '2.4',
                'weight' => 12.5,
            ],
            [
                'product_name' => 'Invalid Product',
                'category' => 'Rings',
                'description' => 'Invalid negative price',
                'status' => 'active',
                'is_featured' => 'no',
                'sku' => 'SKU-INV-1',
                'price' => -100.00, // Invalid
                'stock' => 5,
            ],
        ];

        $job = new ProcessCsvImportChunk($csvImport->id, $chunkData, 2);
        $job->handle();

        $csvImport->refresh();

        $this->assertEquals(2, $csvImport->processed_rows);
        $this->assertEquals(1, $csvImport->successful_rows);
        $this->assertEquals(1, $csvImport->failed_rows);

        // Check created product & variant for valid row
        $product = Product::where('name', 'Gold Bangle')->first();
        $this->assertNotNull($product);
        $this->assertEquals(ProductStatus::Draft, $product->status);
        $variant = ProductVariant::where('sku', 'SKU-GB-1')->first();
        $this->assertNotNull($variant);
        $this->assertEquals(75000.00, $variant->price);
    }
}
