<?php

namespace App\Models;

use App\Models\ProductUnit;
use App\Models\StockHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'product_unit_id', 'creator_id'];

    public function getNameLinkAttribute()
    {
        $title = __('app.show_detail_title', [
            'name' => $this->name, 'type' => __('product.product'),
        ]);
        $link = '<a href="'.route('products.show', $this).'"';
        $link .= ' title="'.$title.'">';
        $link .= $this->name;
        $link .= '</a>';

        return $link;
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class);
    }

    public function getCurrentStock($perDate = null)
    {
        $stockHistoryQuery = $this->stockHistories();

        if ($perDate) {
            return $stockHistoryQuery->where('created_at', '<=', $perDate.' 23:59:59')->sum('amount');
        }

        return $stockHistoryQuery->sum('amount');
    }

    public function unit()
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit_id')->withDefault(['title' => 'n/a']);
    }
}
