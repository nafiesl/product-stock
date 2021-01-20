<?php

namespace App\Http\Controllers;

use App\Models\ProductUnit;
use Illuminate\Http\Request;

class ProductUnitController extends Controller
{
    /**
     * Display a listing of the productUnit.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $editableProductUnit = null;
        $productUnitQuery = ProductUnit::query();
        $productUnitQuery->where('title', 'like', '%'.request('q').'%');
        $productUnits = $productUnitQuery->paginate(25);

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $editableProductUnit = ProductUnit::find(request('id'));
        }

        return view('product_units.index', compact('productUnits', 'editableProductUnit'));
    }

    /**
     * Store a newly created productUnit in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->authorize('create', new ProductUnit);

        $newProductUnit = $request->validate([
            'title'       => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $newProductUnit['creator_id'] = auth()->id();

        ProductUnit::create($newProductUnit);

        return redirect()->route('product_units.index');
    }

    /**
     * Update the specified productUnit in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductUnit  $productUnit
     * @return \Illuminate\Routing\Redirector
     */
    public function update(Request $request, ProductUnit $productUnit)
    {
        $this->authorize('update', $productUnit);

        $productUnitData = $request->validate([
            'title'       => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $productUnit->update($productUnitData);

        $routeParam = request()->only('page', 'q');

        return redirect()->route('product_units.index', $routeParam);
    }

    /**
     * Remove the specified productUnit from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductUnit  $productUnit
     * @return \Illuminate\Routing\Redirector
     */
    public function destroy(Request $request, ProductUnit $productUnit)
    {
        $this->authorize('delete', $productUnit);

        $request->validate(['product_unit_id' => 'required']);

        if ($request->get('product_unit_id') == $productUnit->id && $productUnit->delete()) {
            $routeParam = request()->only('page', 'q');

            return redirect()->route('product_units.index', $routeParam);
        }

        return back();
    }
}
