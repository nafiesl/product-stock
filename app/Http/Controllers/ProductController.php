<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
        $productQuery = Product::query();
        $productQuery->where('name', 'like', '%'.request('q').'%');
        $products = $productQuery->paginate(25);

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

        return view('products.create');
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
            'name'        => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $newProduct['creator_id'] = auth()->id();

        $product = Product::create($newProduct);

        return redirect()->route('products.show', $product);
    }

    /**
     * Display the specified product.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
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

        return view('products.edit', compact('product'));
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
            'name'        => 'required|max:60',
            'description' => 'nullable|max:255',
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
