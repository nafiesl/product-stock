<?php

namespace Tests\Unit\Models;

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\StockHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BrowserKitTest as TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_product_has_name_link_attribute()
    {
        $product = Product::factory()->create();

        $title = __('app.show_detail_title', [
            'name' => $product->name, 'type' => __('product.product'),
        ]);
        $link = '<a href="'.route('products.show', $product).'"';
        $link .= ' title="'.$title.'">';
        $link .= $product->name;
        $link .= '</a>';

        $this->assertEquals($link, $product->name_link);
    }

    /** @test */
    public function a_product_has_belongs_to_creator_relation()
    {
        $product = Product::factory()->make();

        $this->assertInstanceOf(User::class, $product->creator);
        $this->assertEquals($product->creator_id, $product->creator->id);
    }

    /** @test */
    public function a_product_has_many_stock_histories_relation()
    {
        $product = Product::factory()->create();
        $stockHistory = StockHistory::factory()->create(['product_id' => $product->id, 'amount' => 1]);

        $this->assertInstanceOf(Collection::class, $product->stockHistories);
        $this->assertInstanceOf(StockHistory::class, $product->stockHistories->first());
    }

    /** @test */
    public function a_product_has_get_current_stock_method()
    {
        $product = Product::factory()->create();
        StockHistory::factory()->create([
            'product_id' => $product->id,
            'amount'     => 4,
        ]);
        $this->assertEquals($product->getCurrentStock(), 4);

        StockHistory::factory()->create([
            'product_id' => $product->id,
            'amount'     => -1,
        ]);
        $this->assertEquals($product->getCurrentStock(), 3);
    }

    /** @test */
    public function a_product_has_belongs_to_product_unit_relation()
    {
        $productUnit = ProductUnit::factory()->create();
        $product = Product::factory()->create(['product_unit_id' => $productUnit->id]);

        $this->assertInstanceOf(ProductUnit::class, $product->unit);
        $this->assertEquals($product->product_unit_id, $product->unit->id);
    }
}
