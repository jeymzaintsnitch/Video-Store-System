@extends('layouts.app')

@section('title', 'Genre Profile - ' . $category->name)

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background-color: #0f0f15;
        color: #e4e6eb;
    }

    .custom-card {
        border: 1px solid #2d2d3d;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
        background: #161625;
        overflow: hidden;
    }

    .custom-card-header {
        background: #0b0b14;
        color: #fff;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #2d2d3d;
    }

    .custom-card-header h4 {
        margin: 0;
        font-weight: 600;
        font-size: 1.25rem;
        color: #f8f9fa;
    }

    .custom-card-header p {
        margin: 4px 0 0;
        font-size: 13px;
        color: #9fa0a6;
    }

    .info-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #8a8b92;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 15px;
        color: #f8f9fa;
        margin-bottom: 1.5rem;
    }

    .border-dark-custom {
        border-color: #2d2d3d !important;
    }

    .text-secondary-custom {
        color: #b0b3b8 !important;
        line-height: 1.7;
    }
</style>

<div class="container-fluid py-4">
    <div class="custom-card col-lg-9 mx-auto">
        <div class="custom-card-header d-flex justify-content-between align-items-center">
            <div>
                <h4>📑 Genre Registry Card</h4>
                <p>System definition profile and linked catalog items</p>
            </div>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-light btn-sm fw-semibold px-3">← Return to Index</a>
        </div>

        <div class="card-body p-4 row">

            <div class="col-md-5">
                <h5 class="mb-4 pb-2 border-bottom border-dark-custom fw-semibold text-light">Classification Properties</h5>

                <div class="info-label">Genre Block Name</div>
                <div class="info-value">
                    <span class="badge bg-danger bg-opacity-20 text-white border border-danger border-opacity-30 rounded-pill px-4 py-2 fs-5">
                        {{ $category->name }}
                    </span>
                </div>

                <div class="info-label mt-4">Definition Ruleset</div>
                <div class="info-value text-secondary-custom p-3 rounded" style="background-color: #0b0b14; border: 1px solid #2d2d3d;">
                    {{ $category->description ?? 'No specific definition parameters provided.' }}
                </div>

                <div class="d-grid gap-2 mt-4 pt-3 border-top border-dark-custom">
                    @can('edit categories')
                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary py-2 fw-semibold">Edit Genre Profile</a>
                    @endcan

                    @can('delete categories')
                    <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Attempt deletion?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100 py-2 fw-semibold">Delete Definition Block</button>
                    </form>
                    @endcan
                </div>
            </div>

            <div class="col-md-7 border-start border-dark-custom ps-md-4 mt-4 mt-md-0">
                <h5 class="mb-4 pb-2 border-bottom border-dark-custom fw-semibold text-light">Assigned Master Movies</h5>

                <div style="max-height: 420px; overflow-y: auto; padding-right: 5px;">
                    @forelse($category->movies as $movie)
                    <div class="p-3 mb-3 rounded border border-dark-custom" style="background-color: #222235;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small font-monospace text-info mb-1">{{ $movie->movie_id }}</div>
                                <h6 class="text-white mb-0 fw-semibold">{{ $movie->title }} <span class="text-light opacity-75 fw-normal">({{ $movie->year }})</span></h6>
                            </div>
                            <a href="{{ route('movies.show', $movie) }}" class="btn btn-sm btn-dark border border-secondary border-opacity-25 px-3 text-white">View Profile →</a>
                        </div>
                    </div>
                    @empty
                    <div class="p-4 text-center rounded border border-dark-custom" style="background-color: #0b0b14;">
                        <p class="text-light opacity-75 mb-0">No movies are currently classified under this genre.</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection