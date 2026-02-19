<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminLog extends Model
{
    protected $fillable = [
        'admin_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
