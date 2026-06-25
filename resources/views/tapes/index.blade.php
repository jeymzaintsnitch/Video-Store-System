@extends('layouts.app')

@section('title', 'Physical Inventory Management')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background-color: #0f0f15;
        color: #e4e6eb;
    }

    /* Card panel container styles matching Movie theme variables */
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

    /* Filter Input Element Colors */
    .dark-input {
        background-color: #1f1f32 !important;
        border: 1px solid #3a3a52 !important;
        color: #fff !important;
    }

    .dark-input::placeholder {
        color: #787985;
    }

    .dark-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        border-color: #5c63ff;
    }

    /* Complete Dark Table Overhaul - Replaces the white background rows */
    .table-dark-custom {
        margin-bottom: 0;
        background-color: #161625 !important;
    }

    .table-dark-custom thead tr {
        background-color: #0b0b14 !important;
    }

    .table-dark-custom th {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #8a8b92 !important;
        padding: 14px 16px;
        border-bottom: 1px solid #2d2d3d !important;
        background: transparent;
    }

    .table-dark-custom tbody tr {
        background-color: #161625 !important;
        transition: background-color 0.2s ease;
    }

    .table-dark-custom td {
        font-size: 13px;
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #222235 !important;
        color: #ffffff !important;
        background: transparent;
    }

    .table-dark-custom tbody tr:hover {
        background-color: #1f1f32 !important;
    }

    /* Dynamic Data Type Text Color Tokens */
    .tape-num-link {
        color: #38bdf8 !important;
        font-weight: 600;
        text-decoration: none;
    }

    .tape-num-link:hover {
        color: #0d6efd !important;
    }

    .movie-title-text {
        color: #ffffff !important;
        font-weight: 500;
    }

    .shelf-code-text {
        color: #f43f5e !important;
        font-family: monospace;
        font-weight: 600;
        font-size: 14px;
    }
    .badge-available { background-color: rgba(30,142,62,0.2); color: #81c995; border: 1px solid rgba(30,142,62,0.3); font-size:11px; font-weight:600; padding: 4px 10px; border-radius:4px; display:inline-block; }
    .badge-rented    { background-color: rgba(217,48,37,0.2);  color: #f28b82; border: 1px solid rgba(217,48,37,0.3); font-size:11px; font-weight:600; padding: 4px 10px; border-radius:4px; display:inline-block; }
</style>

<div class="container-fluid py-4">
    <div class="custom-card">
        <div class="custom-card-header d-flex justify-content-between align-items-center">
            <div>
                <h4>📼 Physical Tape Inventory</h4>
                <p>Track physical media instances, conditions, and rental status &mdash;
                    <span style="color:#81c995;">{{ $availableCount }} available</span> &bull;
                    <span style="color:#f28b82;">{{ $rentedCount }} rented</span>
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('rentals.index') }}" class="btn btn-outline-info btn-sm fw-semibold px-3">📤 Rentals</a>
                @can('create tapes')
                <a href="{{ route('tapes.create') }}" class="btn btn-light btn-sm fw-semibold px-3">+ Add New Unit</a>
                @endcan
            </div>
        </div>

        <div class="card-body p-4">
            <form method="GET" action="{{ route('tapes.index') }}" class="row g-2 mb-4">
                <div class="col-md-3">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control dark-input" placeholder="Search tracking number...">
                </div>

                <div class="col-md-3">
                    <select name="movie_id" class="form-select dark-input">
                        <option value="">All Movies</option>
                        @foreach($movies as $m)
                        <option value="{{ $m->id }}" {{ request('movie_id') == $m->id ? 'selected' : '' }}>{{ $m->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="format" class="form-select dark-input">
                        <option value="">All Formats</option>
                        <option value="VHS"  {{ request('format') === 'VHS'  ? 'selected' : '' }}>VHS</option>
                        <option value="Beta" {{ request('format') === 'Beta' ? 'selected' : '' }}>Beta</option>
                        <option value="DVD"  {{ request('format') === 'DVD'  ? 'selected' : '' }}>DVD</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-select dark-input">
                        <option value="">All Statuses</option>
                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                        <option value="rented"    {{ request('status') === 'rented'    ? 'selected' : '' }}>Rented</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold" style="font-size:13px;">Filter</button>
                    <a href="{{ route('tapes.index') }}" class="btn btn-outline-light w-100 fw-semibold d-flex align-items-center justify-content-center text-decoration-none" style="font-size:13px;">Reset</a>
                </div>
            </form>
            <div class="table-responsive" style="border-radius: 8px; overflow: hidden; border: 1px solid #2d2d3d;">
                <table class="table table-dark-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Tracking No.</th>
                            <th>Linked Movie Title</th>
                            <th>Format</th>
                            <th>Shelf Allocation</th>
                            <th>Condition</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tapes as $tape)
                        <tr>
                            <td>
                                <a href="{{ route('tapes.show', $tape) }}" class="tape-num-link">
                                    {{ $tape->tape_number }}
                                </a>
                            </td>
                            <td class="movie-title-text">{{ $tape->movie->title ?? 'Orphaned Entry' }}</td>
                            <td>
                                <span class="badge bg-opacity-20 text-white border border-opacity-40 rounded-pill px-2.5 py-1.5
                                        {{ $tape->format == 'DVD' ? 'bg-primary border-primary' : ($tape->format == 'VHS' ? 'bg-warning border-warning' : 'bg-info border-info') }}">
                                    {{ $tape->format }}
                                </span>
                            </td>
                            <td class="shelf-code-text">{{ $tape->shelf_location ?? 'Unassigned' }}</td>
                            <td>
                                <span class="badge rounded-pill px-2 py-1 border border-opacity-20
                                        {{ $tape->condition == 'Good' ? 'bg-success bg-opacity-10 text-success border-success' : ($tape->condition == 'Fair' ? 'bg-warning bg-opacity-10 text-warning border-warning' : 'bg-danger bg-opacity-10 text-danger border-danger') }}">
                                    ● {{ $tape->condition }}
                                </span>
                            </td>
                            <td>
                                @if($tape->status === 'available')
                                    <span class="badge-available">Available</span>
                                @else
                                    <span class="badge-rented">Rented</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('tapes.show', $tape) }}" class="btn btn-outline-light btn-sm px-2">View</a>

                                    @if($tape->status === 'available')
                                    <a href="{{ route('rentals.create', $tape) }}" class="btn btn-outline-success btn-sm px-2">Rent</a>
                                    @endif

                                    @can('edit tapes')
                                    <a href="{{ route('tapes.edit', $tape) }}" class="btn btn-outline-primary btn-sm px-2">Edit</a>
                                    @endcan

                                    @can('delete tapes')
                                    <form method="POST" action="{{ route('tapes.destroy', $tape) }}" onsubmit="return confirm('Permanently discard this physical inventory asset instance?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm px-2">Delete</button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No inventory tracking records match current configurations.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $tapes->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection