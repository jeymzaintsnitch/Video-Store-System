<?php

namespace App\Http\Controllers\Movie;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Category;
use App\Models\Actor;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{

    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $actors     = Actor::orderBy('name')->get();

        $movies = Movie::with(['category', 'actors'])
            ->withCount([
                'tapes',
                'tapes as available_tapes_count' => fn($q) => $q->where('status', 'available'),
                'tapes as rented_tapes_count'    => fn($q) => $q->where('status', 'rented'),
            ])
            ->search($request->search)
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->actor_id,    fn($q) => $q->whereHas('actors', fn($a) => $a->where('actors.id', $request->actor_id)))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        AuditService::log('VIEW', 'Viewed movies list');

        return view('movies.index', compact('movies', 'categories', 'actors'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $actors     = Actor::orderBy('name')->get();
        return view('movies.create', compact('categories', 'actors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'movie_id' => [
                'required', 'string', 'max:20',
                'unique:movies,movie_id',
                'regex:/^MOV-\d{3}$/'
            ],
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'director'    => 'required|string|max:255',
            'year'        => 'required|digits:4|integer|min:1888|max:' . (date('Y') + 2),
            'description' => 'nullable|string',
            'actor_ids'   => 'required|array|min:1',
            'actor_ids.*' => 'exists:actors,id',
            'image'       => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ], [
            'movie_id.regex'     => 'The Catalog ID must follow the format MOV-000 (e.g., MOV-001).',
            'actor_ids.required' => 'You must assign at least one actor to this movie.',
            'actor_ids.min'      => 'You must assign at least one actor to this movie.',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('movies', 'public');
        }

        unset($data['actor_ids']);
        $movie = Movie::create($data);
        $movie->actors()->sync($request->actor_ids);

        AuditService::log('CREATE', "Created movie: {$movie->title} ({$movie->movie_id})", Movie::class, $movie->id, null, $movie->load('actors', 'category')->toArray());

        return redirect()->route('movies.index')
            ->with('success', "Movie \"{$movie->title}\" created successfully.");
    }

    public function show(Movie $movie)
    {
        $movie->load(['category', 'actors', 'tapes']);

        $availableCount = $movie->tapes->where('status', 'available')->count();
        $rentedCount    = $movie->tapes->where('status', 'rented')->count();

        AuditService::log('VIEW', "Viewed movie: {$movie->title}", Movie::class, $movie->id);
        return view('movies.show', compact('movie', 'availableCount', 'rentedCount'));
    }

    public function edit(Movie $movie)
    {
        Gate::authorize('edit movies');
        $categories = Category::orderBy('name')->get();
        $actors     = Actor::orderBy('name')->get();
        $movie->load('actors');
        return view('movies.edit', compact('movie', 'categories', 'actors'));
    }

    public function update(Request $request, Movie $movie)
    {
        Gate::authorize('edit movies');
        $data = $request->validate([
            'movie_id' => [
                'required', 'string', 'max:20',
                'unique:movies,movie_id,' . $movie->id,
                'regex:/^MOV-\d{3}$/'
            ],
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'director'    => 'required|string|max:255',
            'year'        => 'required|digits:4|integer|min:1888|max:' . (date('Y') + 2),
            'description' => 'nullable|string',
            'actor_ids'   => 'required|array|min:1',
            'actor_ids.*' => 'exists:actors,id',
            'image'       => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ], [
            'movie_id.regex'     => 'The Catalog ID must follow the format MOV-000 (e.g., MOV-001).',
            'actor_ids.required' => 'You must assign at least one actor to this movie.',
            'actor_ids.min'      => 'You must assign at least one actor to this movie.',
        ]);

        $old = $movie->load('actors', 'category')->toArray();

        // Handle image replacement
        if ($request->hasFile('image')) {
            if ($movie->image) {
                Storage::disk('public')->delete($movie->image);
            }
            $data['image'] = $request->file('image')->store('movies', 'public');
        }

        unset($data['actor_ids']);
        $movie->update($data);
        $movie->actors()->sync($request->actor_ids);

        AuditService::log('UPDATE', "Updated movie: {$movie->title} ({$movie->movie_id})", Movie::class, $movie->id, $old, $movie->fresh()->load('actors', 'category')->toArray());

        return redirect()->route('movies.index')
            ->with('success', "Movie \"{$movie->title}\" updated successfully.");
    }

    public function destroy(Movie $movie)
    {
        Gate::authorize('delete movies');

        $title           = $movie->title;
        $old             = $movie->toArray();
        $modelInternalId = $movie->id;

        // Delete cover image file
        if ($movie->image) {
            Storage::disk('public')->delete($movie->image);
        }

        $movie->actors()->detach();
        $movie->delete();

        AuditService::log('DELETE', "Permanently deleted movie: {$title}", Movie::class, $modelInternalId, $old);

        return redirect()->route('movies.index')
            ->with('success', "Movie \"{$title}\" was successfully permanently deleted.");
    }
}