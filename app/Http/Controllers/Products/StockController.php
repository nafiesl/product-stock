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
        $dateTime = now();
        if ($request->get('date') && $request->get('time')) {
            $dateTime = $request->get('date').' '.$request->get('time').':00';
        }
        if ($request->has('add_stock')) {
            StockHistory::create([
                'product_id'          => $product->id,
                'partner_id'          => $request->get('partner_id'),
                'transaction_type_id' => $request->get('transaction_type_id'),
                'amount'              => $request->get('amount'),
                'created_at'          => $dateTime,
            ]);
        }
        if ($request->has('subtract_stock')) {
            StockHistory::create([
                'product_id'          => $product->id,
                'partner_id'          => $request->get('partner_id'),
                'transaction_type_id' => $request->get('transaction_type_id'),
                'amount'              => -$request->get('amount'),
                'created_at'          => $dateTime,
            ]);
        }

        return back();
    }
}
