<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\StockHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockHistoryFactory extends Factory
{
    protected $model = StockHistory::class;

    public function definition()
    {
        return [
            'amount'              => random_int(3, 10),
            'transaction_type_id' => StockHistory::TRANSACTION_TYPE_SALES,
            'product_id'          => function () {
                return Product::factory()->create()->id;
            },
        ];
    }
}
