<?php

namespace App\Http\Controllers\Movie;

use App\Models\Actor;
use App\Services\AuditService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class ActorController extends Controller
{

    public function index(Request $request)
    {
        $actors = Actor::withCount('movies')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        AuditService::log('VIEW', 'Viewed actors list');

        return view('actors.index', compact('actors'));
    }

    public function create()
    {
        return view('actors.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'bio'  => 'nullable|string',
        ]);

        $actor = Actor::create($data);

        AuditService::log('CREATE', "Created actor: {$actor->name}", Actor::class, $actor->id, null, $actor->toArray());

        return redirect()->route('actors.index')
            ->with('success', "Actor \"{$actor->name}\" created successfully.");
    }

    public function show(Actor $actor)
    {
        $actor->load('movies.category');
        AuditService::log('VIEW', "Viewed actor: {$actor->name}", Actor::class, $actor->id);
        return view('actors.show', compact('actor'));
    }

    public function edit(Actor $actor)
    {
        Gate::authorize('edit actors');
        return view('actors.edit', compact('actor'));
    }

    public function update(Request $request, Actor $actor)
    {
        Gate::authorize('edit actors');
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'bio'  => 'nullable|string',
        ]);

        $old = $actor->toArray();
        $actor->update($data);

        AuditService::log('UPDATE', "Updated actor: {$actor->name}", Actor::class, $actor->id, $old, $actor->fresh()->toArray());

        return redirect()->route('actors.index')
            ->with('success', "Actor \"{$actor->name}\" updated successfully.");
    }

    public function destroy(Actor $actor)
    {
        Gate::authorize('delete actors');
        $name = $actor->name;
        $old  = $actor->toArray();

        $actor->movies()->detach();
        $actor->delete();

        AuditService::log('DELETE', "Deleted actor: {$name}", Actor::class, $actor->id, $old);

        return redirect()->route('actors.index')
            ->with('success', "Actor \"{$name}\" deleted successfully.");
    }
}