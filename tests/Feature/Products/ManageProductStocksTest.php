<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BrowserKitTest;

class ManageProductStocksTest extends BrowserKitTest
{
    use RefreshDatabase;

    /** @test */
    public function user_can_add_stocks_of_a_product()
    {
        $product = Product::factory()->create();

        $this->visitRoute('products.show', $product);
        $this->seeText($product->name);
        $this->seeElement('input', ['name' => 'amount']);
        $this->seeElement('input', ['name' => 'add_stock', 'value' => __('product.add_stock')]);

        $this->submitForm(__('product.add_stock'), [
            'amount' => '3',
        ]);

        $this->seeRouteIs('products.show', $product);
        $this->seeInDatabase('stock_histories', [
            'product_id' => $product->id,
            'amount'     => 3,
        ]);
    }

    /** @test */
    public function user_can_subtract_stocks_of_a_product()
    {
        $product = Product::factory()->create();

        $this->visitRoute('products.show', $product);
        $this->seeText($product->name);
        $this->seeElement('input', ['name' => 'amount']);
        $this->seeElement('input', ['name' => 'subtract_stock', 'value' => __('product.subtract_stock')]);

        $this->submitForm(__('product.subtract_stock'), [
            'amount' => '3',
        ]);

        $this->seeRouteIs('products.show', $product);
        $this->seeInDatabase('stock_histories', [
            'product_id' => $product->id,
            'amount'     => -3,
        ]);
    }
}
