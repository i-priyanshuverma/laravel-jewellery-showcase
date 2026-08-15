<?php

namespace Tests\Feature\Vendor;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductImageManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_vendor_can_upload_and_delete_product_images(): void
    {
        Storage::fake('public');

        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['user_id' => $vendor->id, 'category_id' => $category->id]);

        // View gallery
        $response = $this->actingAs($vendor)->get(route('vendor.products.images.index', $product));
        $response->assertStatus(200);

        // Upload image
        $file = UploadedFile::fake()->image('ring-front.jpg');
        $uploadResponse = $this->actingAs($vendor)->post(route('vendor.products.images.store', $product), [
            'images' => [$file],
        ]);
        $uploadResponse->assertRedirect();

        $image = ProductImage::where('product_id', $product->id)->first();
        $this->assertNotNull($image);
        Storage::disk('public')->assertExists($image->path);

        // Delete image
        $deleteResponse = $this->actingAs($vendor)->delete(route('vendor.products.images.destroy', [$product, $image]));
        $deleteResponse->assertRedirect();
        $this->assertSoftDeleted('product_images', ['id' => $image->id]);
    }
}
