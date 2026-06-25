<?php

namespace App\Http\Controllers\Movie;

use App\Models\Category;
use App\Services\AuditService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    

    public function index(Request $request)
    {
        $categories = Category::withCount('movies')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        AuditService::log('VIEW', 'Viewed categories list');

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($data);

        AuditService::log('CREATE', "Created category: {$category->name}", Category::class, $category->id, null, $category->toArray());

        return redirect()->route('categories.index')
            ->with('success', "Category \"{$category->name}\" created successfully.");
    }

    public function show(Category $category)
    {
        $category->load('movies');
        AuditService::log('VIEW', "Viewed category: {$category->name}", Category::class, $category->id);
        return view('categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        Gate::authorize('edit categories');
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        Gate::authorize('edit categories');
        $data = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $old = $category->toArray();
        $category->update($data);

        AuditService::log('UPDATE', "Updated category: {$category->name}", Category::class, $category->id, $old, $category->fresh()->toArray());

        return redirect()->route('categories.index')
            ->with('success', "Category \"{$category->name}\" updated successfully.");
    }

    public function destroy(Category $category)
    {
        Gate::authorize('delete categories');
        if ($category->movies()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', "Cannot delete \"{$category->name}\" — it has movies assigned to it.");
        }

        $name = $category->name;
        $old  = $category->toArray();

        $category->delete();

        AuditService::log('DELETE', "Deleted category: {$name}", Category::class, $category->id, $old);

        return redirect()->route('categories.index')
            ->with('success', "Category \"{$name}\" deleted successfully.");
    }
}