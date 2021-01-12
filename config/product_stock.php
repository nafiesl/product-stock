<?php

use App\Models\Partner;
use App\Models\StockHistory;

return [
    'transaction_types' => [
        StockHistory::TRANSACTION_TYPE_SALES    => 'Sales',
        StockHistory::TRANSACTION_TYPE_PURCHASE => 'Purchase',
    ],

    'partner_types' => [
        Partner::TYPE_CUSTOMER => 'Customer',
        Partner::TYPE_VENDOR   => 'Vendor',
    ],
];
