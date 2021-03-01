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
        $transactionTypeString = implode(',', StockHistory::getConstants('TRANSACTION_TYPE'));
        $request->validate([
            'partner_id'          => 'nullable|exists:partners,id',
            'transaction_type_id' => 'required|in:'.$transactionTypeString,
            'amount'              => 'required|numeric',
            'date'                => 'required|date_format:Y-m-d',
            'time'                => 'required|date_format:H:i',
            'description'         => 'nullable|max:255',
        ]);
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
                'description'         => $request->get('description'),
                'created_at'          => $dateTime,
            ]);
        }
        if ($request->has('subtract_stock')) {
            StockHistory::create([
                'product_id'          => $product->id,
                'partner_id'          => $request->get('partner_id'),
                'transaction_type_id' => $request->get('transaction_type_id'),
                'amount'              => -$request->get('amount'),
                'description'         => $request->get('description'),
                'created_at'          => $dateTime,
            ]);
        }

        return back();
    }

    public function update(Request $request, Product $product, StockHistory $stock)
    {
        $transactionTypeString = implode(',', StockHistory::getConstants('TRANSACTION_TYPE'));
        $stockData = $request->validate([
            'partner_id'          => 'nullable|exists:partners,id',
            'transaction_type_id' => 'required|in:'.$transactionTypeString,
            'amount'              => 'required|numeric',
            'date'                => 'required|date_format:Y-m-d',
            'time'                => 'required|date_format:H:i',
            'description'         => 'nullable|max:255',
        ]);
        $dateTime = $request->get('date').' '.$request->get('time').':00';
        $stockData['created_at'] = $dateTime;
        $stock->update($stockData);

        return redirect()->route('products.show', $product);
    }
}
