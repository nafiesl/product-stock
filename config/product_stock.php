<?php

use App\Models\StockHistory;

return [
    'transaction_types' => [
        StockHistory::TRANSACTION_TYPE_SALES    => 'Sales',
        StockHistory::TRANSACTION_TYPE_PURCHASE => 'Purchase',
    ],
];
