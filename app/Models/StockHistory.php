<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    use HasFactory;

    const TRANSACTION_TYPE_SALES = 1;
    const TRANSACTION_TYPE_PURCHASE = 2;

    protected $fillable = ['product_id', 'transaction_type_id', 'amount'];

    public function getTransactionTypeAttribute()
    {
        $transactionTypes = config('product_stock.transaction_types');
        if (!isset($transactionTypes[$this->transaction_type_id])) {
            return;
        }

        if ($this->transaction_type_id == static::TRANSACTION_TYPE_PURCHASE && $this->amount < 0) {
            return $transactionTypes[$this->transaction_type_id].' Return';
        }

        if ($this->transaction_type_id == static::TRANSACTION_TYPE_SALES && $this->amount > 0) {
            return $transactionTypes[$this->transaction_type_id].' Return';
        }

        return $transactionTypes[$this->transaction_type_id];
    }
}
