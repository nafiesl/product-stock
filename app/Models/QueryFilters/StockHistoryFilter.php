<?php

namespace App\Models\QueryFilters;

use Illuminate\Http\Request;

class StockHistoryFilter extends EloquentFilter
{
    public function apply(Request $request)
    {
        $this->filterByPartner($request->get('partner_id'));

        return $this->queryBuilder;
    }

    private function filterByPartner($partnerId)
    {
        if ($partnerId) {
            $this->queryBuilder->where('partner_id', $partnerId);
        }
    }
}
