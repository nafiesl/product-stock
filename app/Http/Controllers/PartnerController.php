<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    /**
     * Display a listing of the partner.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $partnerQuery = Partner::query();
        $partnerQuery->where('name', 'like', '%'.request('q').'%');
        $partners = $partnerQuery->paginate(25);

        return view('partners.index', compact('partners'));
    }

    /**
     * Show the form for creating a new partner.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', new Partner);

        return view('partners.create');
    }

    /**
     * Store a newly created partner in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->authorize('create', new Partner);

        $partnerTypesString = implode(',', array_keys(config('product_stock.partner_types')));
        $newPartner = $request->validate([
            'name'        => 'required|max:60',
            'type_id'     => 'required|in:'.$partnerTypesString,
            'description' => 'nullable|max:255',
        ]);
        $newPartner['creator_id'] = auth()->id();

        $partner = Partner::create($newPartner);

        return redirect()->route('partners.show', $partner);
    }

    /**
     * Display the specified partner.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\View\View
     */
    public function show(Partner $partner)
    {
        return view('partners.show', compact('partner'));
    }

    /**
     * Show the form for editing the specified partner.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\View\View
     */
    public function edit(Partner $partner)
    {
        $this->authorize('update', $partner);

        return view('partners.edit', compact('partner'));
    }

    /**
     * Update the specified partner in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Routing\Redirector
     */
    public function update(Request $request, Partner $partner)
    {
        $this->authorize('update', $partner);

        $partnerTypesString = implode(',', array_keys(config('product_stock.partner_types')));
        $partnerData = $request->validate([
            'name'        => 'required|max:60',
            'type_id'     => 'required|in:'.$partnerTypesString,
            'description' => 'nullable|max:255',
        ]);
        $partner->update($partnerData);

        return redirect()->route('partners.show', $partner);
    }

    /**
     * Remove the specified partner from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Routing\Redirector
     */
    public function destroy(Request $request, Partner $partner)
    {
        $this->authorize('delete', $partner);

        $request->validate(['partner_id' => 'required']);

        if ($request->get('partner_id') == $partner->id && $partner->delete()) {
            return redirect()->route('partners.index');
        }

        return back();
    }
}
