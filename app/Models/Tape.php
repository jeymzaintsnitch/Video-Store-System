<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Tape extends Model
{
    protected $fillable = ['tape_number', 'movie_id', 'format', 'shelf_location', 'condition', 'status'];

    public function movie()   { return $this->belongsTo(Movie::class); }
    public function rentals() { return $this->hasMany(Rental::class); }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }
}