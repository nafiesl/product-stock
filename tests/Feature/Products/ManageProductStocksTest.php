<?php

namespace Tests\Feature\Products;

use App\Models\Partner;
use App\Models\Product;
use App\Models\StockHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BrowserKitTest;

class ManageProductStocksTest extends BrowserKitTest
{
    use RefreshDatabase;

    /** @test */
    public function user_can_add_stocks_of_a_product()
    {
        $this->loginAsUser();
        $product = Product::factory()->create();

        $this->visitRoute('products.show', $product);
        $this->seeText($product->name);
        $this->seeElement('input', ['name' => 'amount']);
        $this->seeElement('input', ['name' => 'add_stock', 'value' => __('product.add_stock')]);

        $this->submitForm(__('product.add_stock'), [
            'transaction_type_id' => StockHistory::TRANSACTION_TYPE_SALES,
            'amount'              => '3',
            'date'                => '2020-01-01',
            'time'                => '21:00',
        ]);

        $this->seeRouteIs('products.show', $product);
        $this->assertEquals($product->getCurrentStock(), 3);
        $this->seeInDatabase('stock_histories', [
            'product_id'          => $product->id,
            'transaction_type_id' => StockHistory::TRANSACTION_TYPE_SALES,
            'amount'              => 3,
            'partner_id'          => null,
            'created_at'          => '2020-01-01 21:00:00',
        ]);
    }

    /** @test */
    public function user_can_subtract_stocks_of_a_product()
    {
        $this->loginAsUser();
        $product = Product::factory()->create();

        $this->visitRoute('products.show', $product);
        $this->seeText($product->name);
        $this->seeElement('input', ['name' => 'amount']);
        $this->seeElement('input', ['name' => 'subtract_stock', 'value' => __('product.subtract_stock')]);

        $this->submitForm(__('product.subtract_stock'), [
            'transaction_type_id' => StockHistory::TRANSACTION_TYPE_PURCHASE,
            'amount'              => '3',
            'date'                => now()->format('Y-m-d'),
            'time'                => now()->format('H:i'),
        ]);

        $this->seeRouteIs('products.show', $product);
        $this->assertEquals($product->getCurrentStock(), -3);
        $this->seeInDatabase('stock_histories', [
            'product_id' => $product->id,
            'amount'     => -3,
            'partner_id' => null,
        ]);
    }

    /** @test */
    public function user_can_add_stocks_of_a_product_with_partner()
    {
        $this->loginAsUser();
        $product = Product::factory()->create();
        $partner = Partner::factory()->create();

        $this->visitRoute('products.show', $product);
        $this->seeText($product->name);
        $this->seeElement('input', ['name' => 'amount']);
        $this->seeElement('input', ['name' => 'add_stock', 'value' => __('product.add_stock')]);

        $this->submitForm(__('product.add_stock'), [
            'transaction_type_id' => StockHistory::TRANSACTION_TYPE_SALES,
            'partner_id'          => $partner->id,
            'amount'              => '3',
            'date'                => now()->format('Y-m-d'),
            'time'                => now()->format('H:i'),
        ]);

        $this->seeRouteIs('products.show', $product);
        $this->assertEquals($product->getCurrentStock(), 3);
        $this->seeInDatabase('stock_histories', [
            'product_id'          => $product->id,
            'transaction_type_id' => StockHistory::TRANSACTION_TYPE_SALES,
            'amount'              => 3,
            'partner_id'          => $partner->id,
        ]);
    }
}
