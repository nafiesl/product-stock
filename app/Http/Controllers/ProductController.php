<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\StockHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the product.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $rawSelect = '`products`.*';
        $rawSelect .= ', (select sum(`amount`) from `stock_histories`';
        $rawSelect .= ' where `products`.`id` = `stock_histories`.`product_id`)';
        $rawSelect .= ' as `current_stock`';
        $productQuery = Product::query();
        $productQuery->where('name', 'like', '%'.request('q').'%');
        $productQuery->selectRaw($rawSelect);
        $products = $productQuery->with('unit')->paginate(25);

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', new Product);
        $productUnits = ProductUnit::pluck('title', 'id');

        return view('products.create', compact('productUnits'));
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->authorize('create', new Product);

        $newProduct = $request->validate([
            'name'            => 'required|max:60',
            'description'     => 'nullable|max:255',
            'product_unit_id' => 'nullable|exists:product_units,id',
        ]);
        $newProduct['creator_id'] = auth()->id();

        $product = Product::create($newProduct);

        return redirect()->route('products.show', $product);
    }

    /**
     * Display the specified product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\View\View
     */
    public function show(Request $request, Product $product)
    {
        $editableStockHistory = null;
        $request->merge([
            'year'  => $request->get('year') ?: now()->format('Y'),
            'month' => $request->get('month') ?: now()->format('m'),
        ]);
        $firstDateOfMonth = $request->get('year').'-'.$request->get('month').'-01';
        $theDate = Carbon::parse($firstDateOfMonth)->subDay()->format('Y-m-d');
        $startingBalance = $product->getCurrentStock($theDate);
        $partners = Partner::orderBy('name')->pluck('name', 'id');
        $stockHistories = $product->stockHistories()->filterBy($request)->oldest('created_at')->with('partner')->get();
        if (request('action') == 'edit_stock_history' && request('stock_history_id')) {
            $editableStockHistory = StockHistory::find(request('stock_history_id'));
        }

        return view('products.show', compact(
            'product', 'partners', 'stockHistories', 'editableStockHistory', 'startingBalance'
        ));
    }

    /**
     * Show the form for editing the specified product.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        $productUnits = ProductUnit::pluck('title', 'id');

        return view('products.edit', compact('product', 'productUnits'));
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Routing\Redirector
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $productData = $request->validate([
            'name'            => 'required|max:60',
            'description'     => 'nullable|max:255',
            'product_unit_id' => 'nullable|exists:product_units,id',
        ]);
        $product->update($productData);

        return redirect()->route('products.show', $product);
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Routing\Redirector
     */
    public function destroy(Request $request, Product $product)
    {
        $this->authorize('delete', $product);

        $request->validate(['product_id' => 'required']);

        if ($request->get('product_id') == $product->id && $product->delete()) {
            return redirect()->route('products.index');
        }

        return back();
    }
}
