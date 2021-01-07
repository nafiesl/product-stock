<?php

namespace Tests\Unit\Models;

use App\Models\StockHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BrowserKitTest as TestCase;

class StockHistoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_stock_history_has_transaction_type_attribute()
    {
        $typeSales = StockHistory::TRANSACTION_TYPE_SALES;
        $stockHistory = StockHistory::factory()->make([
            'transaction_type_id' => $typeSales,
        ]);
        $transactionTypes = config('product_stock.transaction_types');
        $this->assertEquals($transactionTypes[$typeSales], $stockHistory->transaction_type);
    }

    /** @test */
    public function stock_history_checks_addition_or_subtraction_for_transaction_type_attribute()
    {
        $stockHistory = StockHistory::factory()->make();
        $transactionTypes = config('product_stock.transaction_types');

        $typeSales = StockHistory::TRANSACTION_TYPE_PURCHASE;
        $stockHistory->transaction_type_id = $typeSales;
        $stockHistory->amount = 1;
        $this->assertEquals($transactionTypes[$typeSales], $stockHistory->transaction_type);
        $stockHistory->amount = -5;
        $this->assertEquals($transactionTypes[$typeSales].' Return', $stockHistory->transaction_type);

        $typeSales = StockHistory::TRANSACTION_TYPE_SALES;
        $stockHistory->transaction_type_id = $typeSales;
        $stockHistory->amount = 5;
        $this->assertEquals($transactionTypes[$typeSales].' Return', $stockHistory->transaction_type);
        $stockHistory->amount = -3;
        $this->assertEquals($transactionTypes[$typeSales], $stockHistory->transaction_type);
    }
}
