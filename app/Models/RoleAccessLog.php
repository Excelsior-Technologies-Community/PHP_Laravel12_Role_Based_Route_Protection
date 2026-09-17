<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleAccessLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'route',
        'old_role',
        'new_role',
        'old_status',
        'new_status',
        'ip_address',
        'description',
    ];

    protected $casts = [
        'old_status' => 'boolean',
        'new_status' => 'boolean',
    ];

    /**
     * User associated with this audit log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}