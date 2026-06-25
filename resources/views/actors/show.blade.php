@extends('layouts.app')

@section('title', 'Talent Profile - ' . $actor->name)

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
                <h4>👤 Actor Registration Card</h4>
                <p>System Registry Profile and Linked Filmography Data</p>
            </div>
            <a href="{{ route('actors.index') }}" class="btn btn-outline-light btn-sm fw-semibold px-3">← Return to Roster</a>
        </div>

        <div class="card-body p-4 row">

            <div class="col-md-7">
                <h5 class="mb-4 pb-2 border-bottom border-dark-custom fw-semibold text-light">Talent Biography Profile</h5>

                <div class="info-label">Full Stage Name</div>
                <div class="info-value fw-bold text-white fs-4">{{ $actor->name }}</div>

                <div class="info-label">Biographical Details</div>
                <div class="info-value text-secondary-custom p-3 rounded" style="background-color: #0b0b14; border: 1px solid #2d2d3d;">
                    {{ $actor->bio ?? 'No biographical records have been provided for this talent.' }}
                </div>

                <div class="d-flex gap-2 mt-4 pt-3 border-top border-dark-custom">
                    @can('edit actors')
                    <a href="{{ route('actors.edit', $actor) }}" class="btn btn-primary px-4 fw-semibold">Edit Actor Data</a>
                    @endcan

                    @can('delete actors')
                    <form method="POST" action="{{ route('actors.destroy', $actor) }}" onsubmit="return confirm('Permanently remove this actor?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger px-4 fw-semibold">Delete Profile</button>
                    </form>
                    @endcan
                </div>
            </div>

            <div class="col-md-5 border-start border-dark-custom ps-md-4 mt-4 mt-md-0">
                <h5 class="mb-4 pb-2 border-bottom border-dark-custom fw-semibold text-light">Assigned Master Movies</h5>

                <div style="max-height: 380px; overflow-y: auto; padding-right: 5px;">
                    @forelse($actor->movies as $movie)
                    <div class="p-3 mb-3 rounded border border-dark-custom" style="background-color: #222235;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="small font-monospace text-info mb-1">{{ $movie->movie_id }}</div>
                                <h6 class="text-white mb-1 fw-semibold">{{ $movie->title }} ({{ $movie->year }})</h6>
                                <span class="badge bg-danger bg-opacity-20 text-white border border-danger border-opacity-30 rounded-pill px-2 py-1 mt-1 fs-7">
                                    {{ $movie->category->name ?? 'Unclassified' }}
                                </span>
                            </div>
                            <a href="{{ route('movies.show', $movie) }}" class="btn btn-sm btn-dark border border-secondary border-opacity-25 py-1 px-2 text-white mt-1">View →</a>
                        </div>
                    </div>
                    @empty
                    <div class="p-3 text-center rounded border border-dark-custom" style="background-color: #0b0b14;">
                        <p class="text-muted mb-0 small">This actor is not currently assigned to any movie productions in the system.</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection