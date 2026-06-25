@extends('layouts.app')

@section('title', 'System Audit Trail')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background-color: #0f0f15;
        color: #e4e6eb;
    }

    .custom-card {
        background: #161625;
        border: 1px solid #2d2d3d;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
        overflow: hidden;
    }

    .custom-card-header {
        background: #0b0b14;
        color: #fff;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #2d2d3d;
    }

    .dark-input {
        background-color: #1f1f32 !important;
        border: 1px solid #3a3a52 !important;
        color: #fff !important;
        font-size: 13px;
    }

    .dark-input::placeholder {
        color: #787985;
    }

    .dark-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        border-color: #5c63ff;
    }

    .table-dark-custom {
        margin-bottom: 0;
        background-color: transparent !important;
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

    .badge-action {
        font-size: 10px;
        font-weight: 700;
        padding: 5px 10px;
        border-radius: 50px;
        letter-spacing: 0.5px;
    }

    .bg-create {
        background-color: rgba(30, 142, 62, 0.2);
        color: #81c995;
        border: 1px solid rgba(30, 142, 62, 0.3);
    }

    .bg-update {
        background-color: rgba(26, 115, 232, 0.2);
        color: #8ab4f8;
        border: 1px solid rgba(26, 115, 232, 0.3);
    }

    .bg-delete {
        background-color: rgba(217, 48, 37, 0.2);
        color: #f28b82;
        border: 1px solid rgba(217, 48, 37, 0.3);
    }

    .bg-view {
        background-color: rgba(242, 153, 0, 0.2);
        color: #fde293;
        border: 1px solid rgba(242, 153, 0, 0.3);
    }

    .bg-default {
        background-color: rgba(154, 160, 166, 0.2);
        color: #e8eaed;
        border: 1px solid rgba(154, 160, 166, 0.3);
    }

    /* Custom Dark Theme Pagination Overrides */
    .pagination {
        margin-bottom: 0;
        gap: 4px;
    }

    .page-item .page-link {
        background-color: #161625;
        border: 1px solid #2d2d3d;
        color: #a1a1aa;
        border-radius: 6px !important;
        padding: 6px 12px;
        font-size: 13px;
        transition: all 0.2s ease;
    }

    .page-item.active .page-link {
        background-color: #38bdf8;
        border-color: #38bdf8;
        color: #0f0f15;
        font-weight: 600;
    }

    .page-item.disabled .page-link {
        background-color: #0b0b14;
        border-color: #1f1f32;
        color: #535561;
    }

    .page-item:not(.active):not(.disabled) .page-link:hover {
        background-color: #1f1f32;
        border-color: #5c63ff;
        color: #ffffff;
    }

    /* Syncs the default "Showing X to Y of Z results" text with the dark theme */
    .small.text-muted {
        color: #787985 !important;
    }

    nav .d-sm-flex {
        align-items: center !important;
    }

    nav p.small.text-muted {
        color: #8a8b92 !important;
        margin-bottom: 0 !important;
    }

    .pagination {
        margin-bottom: 0;
        gap: 6px;
    }

    .page-item .page-link {
        background-color: #161625;
        border: 1px solid #2d2d3d;
        color: #a1a1aa;
        border-radius: 6px !important;
        padding: 6px 14px;
        font-size: 13px;
        transition: all 0.2s ease;
        box-shadow: none !important;
    }

    .page-item.active .page-link {
        background-color: #38bdf8;
        border-color: #38bdf8;
        color: #0f0f15;
        font-weight: 700;
    }

    .page-item.disabled .page-link {
        background-color: #0b0b14;
        border-color: #1f1f32;
        color: #535561;
        cursor: not-allowed;
    }

    .page-item:not(.active):not(.disabled) .page-link:hover {
        background-color: #1f1f32;
        border-color: #5c63ff;
        color: #ffffff;
    }
</style>

<div class="container-fluid py-4">
    <div class="custom-card col-lg-12 mx-auto">
        <div class="custom-card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="m-0 fw-bold fs-5">📋 System Audit Trail</h4>
                <p class="m-0 mt-1" style="font-size: 12px; color: #9fa0a6;">Immutable record of all database transactions and user activity</p>
            </div>
        </div>

        <div class="card-body p-4">
            <form method="GET" action="{{ route('audit-logs.index') }}" class="row g-2 mb-4 p-3 rounded" style="background-color: #0b0b14; border: 1px solid #2d2d3d;">
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control dark-input h-100" placeholder="Search logs by keyword or user...">
                </div>
                <div class="col-md-2">
                    <select name="action" class="form-select dark-input h-100">
                        <option value="">All Actions</option>
                        @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ $action }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="input-group h-100">
                        <span class="input-group-text dark-input px-2 text-light opacity-50" style="font-size: 12px; border-right: none;">From</span>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control dark-input h-100" style="border-left: none;" onchange="this.form.submit()">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group h-100">
                        <span class="input-group-text dark-input px-3 text-light opacity-50" style="font-size: 12px; border-right: none;">To</span>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control dark-input h-100" style="border-left: none;" onchange="this.form.submit()">
                    </div>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 h-100 fw-semibold" style="font-size: 13px;">Filter</button>
                    <a href="{{ route('audit-logs.index') }}" class="btn btn-outline-light w-100 h-100 fw-semibold d-flex align-items-center justify-content-center text-decoration-none" style="font-size: 13px;">Clear</a>
                </div>
            </form>

            <div class="table-responsive rounded" style="border: 1px solid #2d2d3d;">
                <table class="table table-dark-custom align-middle">
                    <thead>
                        <tr>
                            <th width="15%">Timestamp</th>
                            <th width="15%">Operator</th>
                            <th width="10%">Action</th>
                            <th width="35%">Transaction Summary</th>
                            <th width="15%">IP Target</th>
                            <th width="10%" class="text-end">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td class="font-monospace text-light opacity-50" style="font-size: 11px;">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="fw-semibold text-white">{{ $log->user_name }}</td>
                            <td>
                                @if($log->action === 'CREATE')
                                <span class="badge-action bg-create">CREATE</span>
                                @elseif($log->action === 'UPDATE')
                                <span class="badge-action bg-update">UPDATE</span>
                                @elseif($log->action === 'DELETE')
                                <span class="badge-action bg-delete">DELETE</span>
                                @elseif($log->action === 'VIEW')
                                <span class="badge-action bg-view">VIEW</span>
                                @else
                                <span class="badge-action bg-default">{{ $log->action }}</span>
                                @endif
                            </td>
                            <td class="text-light opacity-75">{{ $log->description }}</td>
                            <td class="font-monospace text-light opacity-50" style="font-size: 11px;">{{ $log->ip_address }}</td>
                            <td class="text-end">
                                <a href="{{ route('audit-logs.show', $log->id) }}" class="btn btn-outline-light btn-sm px-3" style="font-size: 11px;">Inspect</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-light opacity-50 py-5">No audit records match your current filter parameters.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $logs->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection