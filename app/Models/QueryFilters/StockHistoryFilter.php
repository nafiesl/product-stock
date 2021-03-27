<?php

namespace App\Models\QueryFilters;

use Illuminate\Http\Request;

class StockHistoryFilter extends EloquentFilter
{
    public function apply(Request $request)
    {
        $year = $request->get('year');
        $month = $request->get('month');
        $this->filterByYearMonth($year, $month);
        $this->filterByPartner($request->get('partner_id'));

        return $this->queryBuilder;
    }

    private function filterByYearMonth($year, $month)
    {
        $this->queryBuilder->where('created_at', 'like', $year.'-'.$month.'%');
    }

    private function filterByPartner($partnerId)
    {
        if ($partnerId) {
            $this->queryBuilder->where('partner_id', $partnerId);
        }
    }
}
