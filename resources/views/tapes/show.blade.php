@extends('layouts.app')

@section('title', 'Tape Asset Registry Sheet #' . $tape->tape_number)

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
</style>

<div class="container-fluid py-4">
    <div class="custom-card col-lg-7 mx-auto">
        <div class="custom-card-header d-flex justify-content-between align-items-center">
            <div>
                <h4>📼 Tape Asset Profile Sheet</h4>
                <p>System Registry Properties for Instance Token Number: {{ $tape->tape_number }}</p>
            </div>
            <a href="{{ route('tapes.index') }}" class="btn btn-outline-light btn-sm fw-semibold px-3">← Inventory List</a>
        </div>

        <div class="card-body p-4">
            <h5 class="mb-4 pb-2 border-bottom border-dark-custom fw-semibold text-light">Asset Status Variables</h5>

            <div class="row">
                <div class="col-md-6">
                    <div class="info-label">Tracking Barcode ID</div>
                    <div class="info-value font-monospace text-info fs-5 fw-bold">{{ $tape->tape_number }}</div>
                </div>
                <div class="col-md-6">
                    <div class="info-label">Physical Media Format</div>
                    <div class="info-value">
                        <span class="badge bg-opacity-20 text-white border border-opacity-30 rounded-pill px-3 py-1.5 fs-7
                            {{ $tape->format == 'DVD' ? 'bg-primary border-primary' : ($tape->format == 'VHS' ? 'bg-warning border-warning' : 'bg-info border-info') }}">
                            {{ $tape->format }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="info-label">Current Unit Shell Condition</div>
                    <div class="info-value">
                        <span class="badge rounded-pill px-3 py-1.5 fs-7
                            {{ $tape->condition == 'Good' ? 'bg-success bg-opacity-10 text-success' : ($tape->condition == 'Fair' ? 'bg-warning bg-opacity-10 text-warning' : 'bg-danger bg-opacity-10 text-danger') }}">
                            ● Operational Status: {{ $tape->condition }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-label">Allocated Shelf Coordinates</div>
                    <div class="info-value text-white"><code>{{ $tape->shelf_location ?? 'No Specific Coordinates Provisioned' }}</code></div>
                </div>
            </div>

            <h5 class="mb-4 mt-2 pb-2 border-bottom border-dark-custom fw-semibold text-light">Linked Master Media Mapping</h5>

            <div class="p-3 mb-4 rounded border border-dark-custom" style="background-color: #0b0b14;">
                <div class="row align-items-center">
                    <div class="col-sm-8 mb-3 mb-sm-0">
                        <div class="small font-monospace text-secondary mb-1">Catalog Entry: {{ $tape->movie->movie_id ?? 'N/A' }}</div>
                        <h6 class="text-white mb-1 fs-5 fw-semibold">{{ $tape->movie->title ?? 'Orphaned Item Link' }}</h6>
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 rounded-pill px-2.5 py-1">
                            {{ $tape->movie->category->name ?? 'Unclassified' }}
                        </span>
                    </div>
                    <div class="col-sm-4 text-sm-end">
                        @if($tape->movie)
                        <a href="{{ route('movies.show', $tape->movie) }}" class="btn btn-dark btn-sm border border-secondary border-opacity-25 text-light fw-medium">View Master Film Profile →</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row g-2 pt-3 border-top border-dark-custom">
                @can('edit tapes')
                <div class="col-6">
                    <a href="{{ route('tapes.edit', $tape) }}" class="btn btn-primary w-100 py-2 fw-semibold">Edit Unit Variables</a>
                </div>
                @endcan

                @can('delete tapes')
                <div class="col-6">
                    <form method="POST" action="{{ route('tapes.destroy', $tape) }}" onsubmit="return confirm('Permanently wipe this physical media row entry out of the database data fields completely?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100 py-2 fw-semibold">Dispose Registry Row</button>
                    </form>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection