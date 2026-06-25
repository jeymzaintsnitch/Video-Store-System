@extends('layouts.app')

@section('title', 'Movie Registry Profile - ' . $movie->title)

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body { background-color: #0f0f15; color: #e4e6eb; }
    .custom-card { border: 1px solid #2d2d3d; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.4); background: #161625; overflow: hidden; }
    .custom-card-header { background: #0b0b14; color: #fff; padding: 1.25rem 1.5rem; border-bottom: 1px solid #2d2d3d; }
    .custom-card-header h4 { margin: 0; font-weight: 600; font-size: 1.25rem; color: #f8f9fa; }
    .custom-card-header p { margin: 4px 0 0; font-size: 13px; color: #9fa0a6; }
    .info-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; color: #8a8b92; font-weight: 700; margin-bottom: 4px; }
    .info-value { font-size: 15px; color: #f8f9fa; margin-bottom: 1.5rem; }
    .actor-badge { background: #222235; color: #e4e6eb; border: 1px solid #3a3a52; padding: 6px 14px; border-radius: 50px; font-size: 13px; display: inline-block; margin-right: 6px; margin-bottom: 8px; font-weight: 500; }
    .text-secondary-custom { color: #b0b3b8 !important; }
    .border-dark-custom { border-color: #2d2d3d !important; }

    .movie-cover { width: 100%; border-radius: 10px; border: 1px solid #3a3a52; object-fit: cover; max-height: 300px; }
    .movie-cover-placeholder { width: 100%; height: 200px; background: #0b0b14; border: 1px solid #3a3a52; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 60px; }

    .tape-status-pill { padding: 6px 16px; border-radius: 50px; font-size: 13px; font-weight: 600; display: inline-block; }
    .pill-available { background-color: rgba(30,142,62,0.2); color: #81c995; border: 1px solid rgba(30,142,62,0.35); }
    .pill-rented    { background-color: rgba(217,48,37,0.2);  color: #f28b82; border: 1px solid rgba(217,48,37,0.35); }

    .tape-row { background: #0b0b14; border: 1px solid #2d2d3d; border-radius: 8px; padding: 10px 14px; margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center; }
</style>

<div class="container-fluid py-4">
    <div class="custom-card">
        <div class="custom-card-header d-flex justify-content-between align-items-center">
            <div>
                <h4>🎬 Movie Registry Profile</h4>
                <p>System Token Properties for Catalog ID: {{ $movie->movie_id }}</p>
            </div>
            <a href="{{ route('movies.index') }}" class="btn btn-outline-light btn-sm fw-semibold px-3">← Back to Catalog</a>
        </div>

        <div class="card-body p-4">
            <div class="row">

                {{-- Cover image column --}}
                <div class="col-md-3 mb-4 mb-md-0">
                    @if($movie->image)
                        <img src="{{ asset('storage/' . $movie->image) }}" class="movie-cover" alt="{{ $movie->title }}">
                    @else
                        <div class="movie-cover-placeholder">🎬</div>
                    @endif

                    {{-- Rental availability summary --}}
                    <div class="mt-3 text-center">
                        <div class="tape-status-pill pill-available mb-2">✅ {{ $availableCount }} Available</div>
                        <div class="tape-status-pill pill-rented">📤 {{ $rentedCount }} Rented</div>
                    </div>
                </div>

                {{-- Core metadata --}}
                <div class="col-md-5">
                    <h5 class="mb-4 pb-2 border-bottom border-dark-custom fw-semibold text-light">Core Asset Metadata</h5>

                    <div class="info-label">Movie Title</div>
                    <div class="info-value fw-semibold text-white fs-5">{{ $movie->title }}</div>

                    <div class="row">
                        <div class="col-6">
                            <div class="info-label">Genre / Classification</div>
                            <div class="info-value">
                                <span class="badge bg-danger bg-opacity-20 text-white border border-danger border-opacity-30 rounded-pill px-3 py-1">
                                    {{ $movie->category->name }}
                                </span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-label">Production Year</div>
                            <div class="info-value text-light">{{ $movie->year }}</div>
                        </div>
                    </div>

                    <div class="info-label">Director / Creator</div>
                    <div class="info-value text-light">{{ $movie->director }}</div>

                    <div class="info-label">Description / Logline Summary</div>
                    <div class="info-value text-secondary-custom" style="white-space: pre-line; line-height: 1.6;">
                        {{ $movie->description ?? 'No summary logline registry provided for this entry.' }}
                    </div>
                </div>

                {{-- Right column: cast + actions --}}
                <div class="col-md-4 border-start border-dark-custom ps-md-4">
                    <h5 class="mb-4 pb-2 border-bottom border-dark-custom fw-semibold text-light">Linked Production Cast</h5>
                    <div class="mb-4">
                        @forelse($movie->actors as $actor)
                            <div class="actor-badge">👤 {{ $actor->name }}</div>
                        @empty
                            <p class="text-muted small">No talent personnel assigned.</p>
                        @endforelse
                    </div>

                    {{-- Physical copies --}}
                    <h5 class="mb-3 pb-2 border-bottom border-dark-custom fw-semibold text-light">Physical Copies</h5>
                    <div class="mb-4" style="max-height: 200px; overflow-y: auto;">
                        @forelse($movie->tapes as $tape)
                            <div class="tape-row">
                                <div>
                                    <span class="text-info font-monospace small">{{ $tape->tape_number }}</span>
                                    <span class="text-muted small ms-2">{{ $tape->format }}</span>
                                </div>
                                <div>
                                    @if($tape->status === 'available')
                                        <a href="{{ route('rentals.create', $tape) }}" class="btn btn-sm btn-success py-0 px-2 fw-semibold" style="font-size: 11px;">Rent</a>
                                    @else
                                        <span class="badge" style="background: rgba(217,48,37,0.2); color:#f28b82; border:1px solid rgba(217,48,37,0.35); font-size:11px;">Rented</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-muted small">No physical copies registered.</p>
                        @endforelse
                    </div>

                    <h5 class="mb-3 pb-2 border-bottom border-dark-custom fw-semibold text-light">System Actions</h5>
                    <div class="d-grid gap-2">
                        @if($availableCount > 0)
                        <a href="{{ route('movies.rent', $movie) }}" class="btn btn-success btn-sm py-2 fw-bold text-uppercase mb-2 shadow">Rent This Movie</a>
                        @endif

                        @can('edit movies')
                        <a href="{{ route('movies.edit', $movie) }}" class="btn btn-primary btn-sm py-2 fw-semibold">Edit Title Properties</a>
                        @endcan

                        @can('delete movies')
                        <form method="POST" action="{{ route('movies.destroy', $movie) }}" onsubmit="return confirm('Permanently delete this movie?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100 py-2">Delete Movie Entry</button>
                        </form>
                        @endcan
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection