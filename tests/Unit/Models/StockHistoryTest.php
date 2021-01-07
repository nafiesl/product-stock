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
}
