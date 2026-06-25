<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $fillable = ['tape_id', 'rented_by', 'rented_at', 'due_date', 'returned_at'];

    protected $casts = [
        'rented_at'   => 'date',
        'due_date'    => 'date',
        'returned_at' => 'date',
    ];

    public function tape() { return $this->belongsTo(Tape::class); }

    public function isActive(): bool
    {
        return $this->returned_at === null;
    }

    public function isOverdue(): bool
    {
        return $this->isActive() && $this->due_date->isPast();
    }
}
