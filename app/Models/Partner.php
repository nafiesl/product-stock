<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    const TYPE_CUSTOMER = 1;
    const TYPE_VENDOR = 2;

    protected $fillable = ['name', 'type_id', 'description', 'creator_id'];

    public function getNameLinkAttribute()
    {
        return link_to_route('partners.show', $this->name, [$this], [
            'title' => __(
                'app.show_detail_title',
                ['name' => $this->name, 'type' => __('partner.partner')]
            ),
        ]);
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }
}
