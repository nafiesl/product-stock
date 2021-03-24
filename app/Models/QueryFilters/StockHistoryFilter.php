<?php

namespace App\Models\QueryFilters;

use Illuminate\Http\Request;

class StockHistoryFilter extends EloquentFilter
{
    public function apply(Request $request)
    {
        // Start filtering here..

        return $this->queryBuilder;
    }
}
