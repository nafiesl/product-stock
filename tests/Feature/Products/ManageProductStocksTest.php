<?php

namespace Tests\Feature\Products;

use App\Models\Partner;
use App\Models\Product;
use App\Models\StockHistory;
use Carbon\Carbon;
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

    /** @test */
    public function user_can_add_stocks_of_a_product_with_description()
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
            'description'         => 'Testing description',
        ]);

        $this->seeRouteIs('products.show', $product);
        $this->assertEquals($product->getCurrentStock(), 3);
        $this->seeInDatabase('stock_histories', [
            'product_id'          => $product->id,
            'transaction_type_id' => StockHistory::TRANSACTION_TYPE_SALES,
            'amount'              => 3,
            'partner_id'          => $partner->id,
            'description'         => 'Testing description',
        ]);
    }

    /** @test */
    public function user_can_edit_stock_history()
    {
        $this->loginAsUser();
        $product = Product::factory()->create();
        $partner = Partner::factory()->create();
        $stockHistory = StockHistory::factory()->create([
            'transaction_type_id' => StockHistory::TRANSACTION_TYPE_SALES,
            'partner_id'          => $partner->id,
            'product_id'          => $product->id,
            'amount'              => '3',
            'description'         => 'Testing description',
        ]);

        $this->visitRoute('products.show', $product);
        $this->seeText('Testing description');
        $this->seeElement('a', [
            'href' => route('products.show', [$product->id, 'action' => 'edit_stock_history', 'stock_history_id' => $stockHistory->id]),
            'id'   => 'edit_stock-'.$stockHistory->id,
        ]);

        $this->click('edit_stock-'.$stockHistory->id);
        $this->submitForm(__('product.update_stock'), [
            'transaction_type_id' => StockHistory::TRANSACTION_TYPE_PURCHASE,
            'partner_id'          => $partner->id,
            'amount'              => '3',
            'date'                => now()->format('Y-m-d'),
            'time'                => now()->format('H:i'),
            'description'         => 'Testing description updated',
        ]);

        $this->seeInDatabase('stock_histories', [
            'id'                  => $stockHistory->id,
            'product_id'          => $product->id,
            'transaction_type_id' => StockHistory::TRANSACTION_TYPE_PURCHASE,
            'amount'              => 3,
            'partner_id'          => $partner->id,
            'description'         => 'Testing description updated',
        ]);
    }

    /** @test */
    public function browse_product_stock_history_monthly()
    {
        Carbon::setTestNow('2020-02-28');
        $this->loginAsUser();
        $product = Product::factory()->create();
        $partner = Partner::factory()->create();
        $currentMonthStockHistory = StockHistory::factory()->create([
            'transaction_type_id' => StockHistory::TRANSACTION_TYPE_SALES,
            'partner_id'          => $partner->id,
            'product_id'          => $product->id,
            'amount'              => '3',
            'description'         => 'Testing current month description',
            'created_at'          => '2020-02-20',
        ]);
        $lastMonthStockHistory = StockHistory::factory()->create([
            'transaction_type_id' => StockHistory::TRANSACTION_TYPE_SALES,
            'partner_id'          => $partner->id,
            'product_id'          => $product->id,
            'amount'              => '32',
            'description'         => 'Testing last month description',
            'created_at'          => '2020-01-20',
        ]);

        $this->visitRoute('products.show', $product);
        $this->seeText($currentMonthStockHistory->description);
        $this->dontSeeText($lastMonthStockHistory->description);
        $this->see('<span id="last_periode_stock">32</span>');
        $this->see('<span id="current_periode_stock">35</span>');
        Carbon::setTestNow();
    }

    /** @test */
    public function browse_product_stock_history_by_selecting_month_and_year()
    {
        Carbon::setTestNow('2020-01-28');
        $this->loginAsUser();
        $product = Product::factory()->create();
        $partner = Partner::factory()->create();
        $currentMonthStockHistory = StockHistory::factory()->create([
            'transaction_type_id' => StockHistory::TRANSACTION_TYPE_SALES,
            'partner_id'          => $partner->id,
            'product_id'          => $product->id,
            'amount'              => '3',
            'description'         => 'Testing current month description',
            'created_at'          => '2020-01-20',
        ]);
        $lastMonthStockHistory = StockHistory::factory()->create([
            'transaction_type_id' => StockHistory::TRANSACTION_TYPE_SALES,
            'partner_id'          => $partner->id,
            'product_id'          => $product->id,
            'amount'              => '3',
            'description'         => 'Testing last month description',
            'created_at'          => '2019-12-20',
        ]);

        $this->visitRoute('products.show', $product);
        $this->submitForm(__('app.submit'), [
            'year'  => '2019',
            'month' => '12',
        ]);
        $this->seeRouteIs('products.show', [$product, 'action' => 'filter', 'month' => '12', 'partner_id' => '', 'year' => '2019']);
        $this->dontSeeText($currentMonthStockHistory->description);
        $this->seeText($lastMonthStockHistory->description);
        $this->see('<span id="last_periode_stock">0</span>');
        $this->see('<span id="current_periode_stock">3</span>');
        Carbon::setTestNow();
    }
}
