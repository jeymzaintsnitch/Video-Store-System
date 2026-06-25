<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    // Disables timestamps since your migration only uses created_at
    public $timestamps = false;

    // Allows mass assignment for the audit trail attributes
    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'model_type',
        'model_id',
        'description',
        'ip_address',
        'user_agent',
        'old_values',
        'new_values',
        'created_at'
    ];

    // Explicitly casts the JSON arrays back to PHP arrays when read
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime'
    ];

    /**
     * Relationship back to the User model
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}