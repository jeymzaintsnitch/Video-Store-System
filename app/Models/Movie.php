<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Movie extends Model
{
    protected $fillable = ['movie_id', 'title', 'category_id', 'director', 'year', 'description', 'image'];

    /**
     * Use 'movie_id' as the route key for URL binding.
     */
    public function getRouteKeyName()
    {
        return 'movie_id';
    }

    // Relationships
    public function category()   { return $this->belongsTo(Category::class); }
    public function tapes()      { return $this->hasMany(Tape::class); }
    public function actors()     { return $this->belongsToMany(Actor::class, 'movie_actor'); }

    // Scopes
    /**
     * Filter movies by title, director, movie_id, actor name, or category name.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) return $query;

        return $query->where(function (Builder $q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('director', 'like', "%{$term}%")
              ->orWhere('movie_id', 'like', "%{$term}%")
              ->orWhereHas('actors', fn($a) => $a->where('name', 'like', "%{$term}%"))
              ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$term}%"));
        });
    }

    /**
     * Only movies that have at least one available tape.
     */
    public function scopeAvailableForRent(Builder $query): Builder
    {
        return $query->whereHas('tapes', fn($q) => $q->where('status', 'available'));
    }

    /**
     * Get the cover image URL or a placeholder.
     */
    public function getCoverUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/no-cover.png');
    }
}