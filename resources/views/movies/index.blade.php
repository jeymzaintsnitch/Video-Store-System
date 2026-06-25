@extends('layouts.app')

@section('title', 'Movies Management')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body { background-color: #0f0f15; color: #e4e6eb; }

    .custom-card { border: 1px solid #2d2d3d; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.4); background: #161625; overflow: hidden; }
    .custom-card-header { background: #0b0b14; color: #fff; padding: 1.25rem 1.5rem; border-bottom: 1px solid #2d2d3d; }
    .custom-card-header h4 { margin: 0; font-weight: 600; font-size: 1.25rem; color: #f8f9fa; }
    .custom-card-header p { margin: 4px 0 0; font-size: 13px; color: #9fa0a6; }

    .dark-input { background-color: #1f1f32 !important; border: 1px solid #3a3a52 !important; color: #fff !important; }
    .dark-input::placeholder { color: #787985; }
    .dark-input:focus { box-shadow: 0 0 0 0.25rem rgba(13,110,253,0.25); border-color: #5c63ff; }

    .table-dark-custom { margin-bottom: 0; background-color: #161625 !important; }
    .table-dark-custom thead tr { background-color: #0b0b14 !important; }
    .table-dark-custom th { font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; color: #8a8b92 !important; padding: 14px 16px; border-bottom: 1px solid #2d2d3d !important; background: transparent; }
    .table-dark-custom tbody tr { background-color: #161625 !important; transition: background-color 0.2s ease; }
    .table-dark-custom td { font-size: 13px; padding: 12px 16px; vertical-align: middle; border-bottom: 1px solid #222235 !important; color: #b0b3b8 !important; background: transparent; }
    .table-dark-custom tbody tr:hover { background-color: #1f1f32 !important; }
    .table-dark-custom tbody tr:hover td { color: #f8f9fa !important; }

    .movie-title-link { color: #ffffff !important; font-weight: 600; text-decoration: none; }
    .movie-title-link:hover { color: #3b82f6 !important; }
    .catalog-id-text { color: #38bdf8 !important; font-weight: 500; opacity: 0.85; }

    .movie-thumb { width: 40px; height: 56px; object-fit: cover; border-radius: 5px; border: 1px solid #3a3a52; }
    .movie-thumb-placeholder { width: 40px; height: 56px; background: #222235; border-radius: 5px; border: 1px solid #3a3a52; display: flex; align-items: center; justify-content: center; font-size: 18px; }

    .badge-available { background-color: rgba(30,142,62,0.2); color: #81c995; border: 1px solid rgba(30,142,62,0.3); }
    .badge-rented    { background-color: rgba(217,48,37,0.2);  color: #f28b82; border: 1px solid rgba(217,48,37,0.3); }
    .badge-tapes     { background-color: #222235; color: #e4e6eb; border: 1px solid #3a3a52; }
</style>

<div class="container-fluid py-4">
    <div class="custom-card">
        <div class="custom-card-header d-flex justify-content-between align-items-center">
            <div>
                <h4>🎞 Movie Inventory Catalog</h4>
                <p>Track cinematic titles, categories, and assigned inventory volumes</p>
            </div>
            @can('create movies')
            <a href="{{ route('movies.create') }}" class="btn btn-light btn-sm fw-semibold px-3">+ Add Movie Title</a>
            @endcan
        </div>

        <div class="card-body p-4">
            {{-- Search Filters --}}
            <form method="GET" action="{{ route('movies.index') }}" class="row g-3 mb-4">
                <div class="col-md-5">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control dark-input"
                        placeholder="Search by title, director, actor name, or genre...">
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select dark-input">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="actor_id" class="form-select dark-input">
                        <option value="">All Actors</option>
                        @foreach($actors as $actor)
                        <option value="{{ $actor->id }}" {{ request('actor_id') == $actor->id ? 'selected' : '' }}>{{ $actor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">Filter</button>
                    <a href="{{ route('movies.index') }}" class="btn btn-outline-light w-100 fw-semibold d-flex align-items-center justify-content-center text-decoration-none">Reset</a>
                </div>
            </form>

            <div class="table-responsive" style="border-radius: 8px; overflow: hidden; border: 1px solid #2d2d3d;">
                <table class="table table-dark-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:50px;">Cover</th>
                            <th>Catalog ID</th>
                            <th>Movie Title</th>
                            <th>Genre</th>
                            <th>Director</th>
                            <th>Year</th>
                            <th>Available</th>
                            <th>Rented</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movies as $movie)
                        <tr>
                            <td>
                                @if($movie->image)
                                    <img src="{{ asset('storage/' . $movie->image) }}" class="movie-thumb" alt="{{ $movie->title }}">
                                @else
                                    <div class="movie-thumb-placeholder">🎬</div>
                                @endif
                            </td>
                            <td class="font-monospace catalog-id-text">{{ $movie->movie_id }}</td>
                            <td>
                                <a href="{{ route('movies.show', $movie) }}" class="movie-title-link">{{ $movie->title }}</a>
                            </td>
                            <td>
                                <span class="badge bg-danger bg-opacity-20 text-white border border-danger border-opacity-20 rounded-pill px-2 py-1">
                                    {{ $movie->category->name }}
                                </span>
                            </td>
                            <td class="text-light opacity-75">{{ $movie->director }}</td>
                            <td class="text-light opacity-75">{{ $movie->year }}</td>
                            <td>
                                <span class="badge badge-available px-2 py-1 rounded-pill">
                                    {{ $movie->available_tapes_count }} available
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-rented px-2 py-1 rounded-pill">
                                    {{ $movie->rented_tapes_count }} rented
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    @if($movie->available_tapes_count > 0)
                                    <a href="{{ route('movies.rent', $movie) }}" class="btn btn-success btn-sm px-2 fw-semibold">Rent</a>
                                    @endif
                                    <a href="{{ route('movies.show', $movie) }}" class="btn btn-outline-light btn-sm px-2">View</a>

                                    @can('edit movies')
                                    <a href="{{ route('movies.edit', $movie) }}" class="btn btn-outline-primary btn-sm px-2">Edit</a>
                                    @endcan

                                    @can('delete movies')
                                    <form method="POST" action="{{ route('movies.destroy', $movie) }}" onsubmit="return confirm('Permanently delete this movie?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm px-2">Delete</button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No video catalog records match this filtering.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $movies->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection