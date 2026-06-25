@extends('layouts.app')

@section('title', 'Active Rentals')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body { background-color: #0f0f15; color: #e4e6eb; }
    .custom-card { border: 1px solid #2d2d3d; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.4); background: #161625; overflow: hidden; }
    .custom-card-header { background: #0b0b14; color: #fff; padding: 1.25rem 1.5rem; border-bottom: 1px solid #2d2d3d; }
    .custom-card-header h4 { margin: 0; font-weight: 600; font-size: 1.25rem; color: #f8f9fa; }
    .custom-card-header p { margin: 4px 0 0; font-size: 13px; color: #9fa0a6; }
    .dark-input { background-color: #1f1f32 !important; border: 1px solid #3a3a52 !important; color: #fff !important; }
    .dark-input option { background: #1f1f32; }

    .table-dark-custom { margin-bottom: 0; background-color: #161625 !important; }
    .table-dark-custom thead tr { background-color: #0b0b14 !important; }
    .table-dark-custom th { font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; color: #8a8b92 !important; padding: 14px 16px; border-bottom: 1px solid #2d2d3d !important; background: transparent; }
    .table-dark-custom tbody tr { background-color: #161625 !important; transition: background-color 0.2s; }
    .table-dark-custom td { font-size: 13px; padding: 12px 16px; vertical-align: middle; border-bottom: 1px solid #222235 !important; color: #b0b3b8 !important; background: transparent; }
    .table-dark-custom tbody tr:hover { background-color: #1f1f32 !important; }

    .badge-overdue  { background-color: rgba(217,48,37,0.25);  color: #f28b82; border: 1px solid rgba(217,48,37,0.4); }
    .badge-active   { background-color: rgba(26,115,232,0.2);  color: #8ab4f8; border: 1px solid rgba(26,115,232,0.3); }
    .badge-returned { background-color: rgba(30,142,62,0.2);   color: #81c995; border: 1px solid rgba(30,142,62,0.3); }

    .status-badge { font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 4px; display: inline-block; }
    .filter-btn { font-size: 12px; font-weight: 600; padding: 6px 14px; border-radius: 6px; }
    .filter-btn.active-btn { background: #1a73e8; color: #fff; border: 1px solid #1a73e8; }
    .filter-btn.inactive-btn { background: transparent; color: #8a8b92; border: 1px solid #3a3a52; }
</style>

<div class="container-fluid py-4">
    <div class="custom-card">
        <div class="custom-card-header d-flex justify-content-between align-items-center">
            <div>
                <h4>📤 Rental Management</h4>
                <p>Track all tape rentals, due dates, and returns</p>
            </div>
        </div>

        <div class="card-body p-4">
            {{-- Status Filter --}}
            <div class="d-flex gap-2 mb-4">
                <a href="{{ route('rentals.index', ['status' => 'active']) }}"
                   class="filter-btn {{ (!request('status') || request('status') === 'active') ? 'active-btn' : 'inactive-btn' }} text-decoration-none">
                    🔵 Active
                </a>
                <a href="{{ route('rentals.index', ['status' => 'returned']) }}"
                   class="filter-btn {{ request('status') === 'returned' ? 'active-btn' : 'inactive-btn' }} text-decoration-none">
                    ✅ Returned
                </a>
                <a href="{{ route('rentals.index', ['status' => 'all']) }}"
                   class="filter-btn {{ request('status') === 'all' ? 'active-btn' : 'inactive-btn' }} text-decoration-none">
                    📋 All
                </a>
            </div>

            <div class="table-responsive" style="border-radius: 8px; overflow: hidden; border: 1px solid #2d2d3d;">
                <table class="table table-dark-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tape No.</th>
                            <th>Movie</th>
                            <th>Rented By</th>
                            <th>Rented On</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Returned</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rentals as $rental)
                        <tr>
                            <td class="text-muted">{{ $rental->id }}</td>
                            <td class="font-monospace text-info">{{ $rental->tape->tape_number ?? '—' }}</td>
                            <td class="text-white fw-semibold">{{ $rental->tape->movie->title ?? '—' }}</td>
                            <td>{{ $rental->rented_by }}</td>
                            <td>{{ $rental->rented_at->format('M d, Y') }}</td>
                            <td>
                                @if($rental->isActive() && $rental->isOverdue())
                                    <span class="text-danger fw-semibold">{{ $rental->due_date->format('M d, Y') }} ⚠️</span>
                                @else
                                    {{ $rental->due_date->format('M d, Y') }}
                                @endif
                            </td>
                            <td>
                                @if($rental->returned_at)
                                    <span class="status-badge badge-returned">Returned</span>
                                @elseif($rental->isOverdue())
                                    <span class="status-badge badge-overdue">Overdue</span>
                                @else
                                    <span class="status-badge badge-active">Active</span>
                                @endif
                            </td>
                            <td>{{ $rental->returned_at ? $rental->returned_at->format('M d, Y') : '—' }}</td>
                            <td class="text-end">
                                @if(!$rental->returned_at)
                                <form method="POST" action="{{ route('rentals.return', $rental) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm px-3">Return</button>
                                </form>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No rentals found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $rentals->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
