<?php

namespace Tests\Unit\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\ProductSearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSearchServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ProductSearchService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ProductSearchService;
    }

    public function test_search_by_product_name_sku_and_vendor_name(): void
    {
        $vendor = User::factory()->vendor()->approved()->create(['name' => 'Royal Jewellers']);
        $vendor->vendorProfile()->create(['business_name' => 'Royal Heritage']);

        $cat = Category::factory()->create(['name' => 'Rings']);

        $product1 = Product::factory()->active()->create(['user_id' => $vendor->id, 'category_id' => $cat->id, 'name' => 'Diamond Empress Ring']);
        ProductVariant::factory()->active()->create(['product_id' => $product1->id, 'sku' => 'EMP-100', 'price' => 50000]);

        $product2 = Product::factory()->active()->create(['user_id' => $vendor->id, 'category_id' => $cat->id, 'name' => 'Gold Choker']);
        ProductVariant::factory()->active()->create(['product_id' => $product2->id, 'sku' => 'CHK-200', 'price' => 90000]);

        // Search by product name
        $results = $this->service->search(['search' => 'Empress']);
        $this->assertEquals(1, $results->total());
        $this->assertEquals($product1->id, $results->first()->id);

        // Search by SKU
        $resultsSKU = $this->service->search(['search' => 'CHK-200']);
        $this->assertEquals(1, $resultsSKU->total());
        $this->assertEquals($product2->id, $resultsSKU->first()->id);

        // Search by Vendor business name
        $resultsVendor = $this->service->search(['search' => 'Royal Heritage']);
        $this->assertEquals(2, $resultsVendor->total());
    }

    public function test_filters_by_category_price_and_stock(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $catRings = Category::factory()->create(['name' => 'Rings']);
        $catNecklaces = Category::factory()->create(['name' => 'Necklaces']);

        $ring = Product::factory()->active()->create(['user_id' => $vendor->id, 'category_id' => $catRings->id]);
        ProductVariant::factory()->active()->create(['product_id' => $ring->id, 'price' => 25000, 'stock' => 5]);

        $necklace = Product::factory()->active()->create(['user_id' => $vendor->id, 'category_id' => $catNecklaces->id]);
        ProductVariant::factory()->outOfStock()->active()->create(['product_id' => $necklace->id, 'price' => 150000]);

        // Category filter
        $resultsCat = $this->service->search(['category_id' => $catRings->id]);
        $this->assertEquals(1, $resultsCat->total());

        // Price filter
        $resultsPrice = $this->service->search(['max_price' => 30000]);
        $this->assertEquals(1, $resultsPrice->total());

        // In Stock filter
        $resultsStock = $this->service->search(['in_stock' => '1']);
        $this->assertEquals(1, $resultsStock->total());
        $this->assertEquals($ring->id, $resultsStock->first()->id);
    }
}
