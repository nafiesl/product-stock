<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    use HasFactory;

    const TRANSACTION_TYPE_SALES = 1;

    protected $fillable = ['product_id', 'transaction_type_id', 'amount'];
}
