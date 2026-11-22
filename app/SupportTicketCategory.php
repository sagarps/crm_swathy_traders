<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class SupportTicketCategory extends Model
{
    use LogsActivity;

    protected $table = 'support_ticket_categories';

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function tickets()
    {
        return $this->hasMany(SupportTicket::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('name');
    }
}

