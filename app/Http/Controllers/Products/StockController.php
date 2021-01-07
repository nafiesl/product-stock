<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockHistory;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function store(Product $product, Request $request)
    {
        if ($request->has('add_stock')) {
            StockHistory::create([
                'product_id'          => $product->id,
                'transaction_type_id' => $request->get('transaction_type_id'),
                'amount'              => $request->get('amount'),
            ]);
        }
        if ($request->has('subtract_stock')) {
            StockHistory::create([
                'product_id'          => $product->id,
                'transaction_type_id' => $request->get('transaction_type_id'),
                'amount'              => -$request->get('amount'),
            ]);
        }

        return back();
    }
}
