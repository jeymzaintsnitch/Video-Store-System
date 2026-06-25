@extends('layouts.app')

@section('title', 'Audit Log Inspection')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body { background-color: #0f0f15; color: #e4e6eb; }
    .custom-card { background: #161625; border: 1px solid #2d2d3d; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.4); overflow: hidden; }
    .custom-card-header { background: #0b0b14; color: #fff; padding: 1.25rem 1.5rem; border-bottom: 1px solid #2d2d3d; }
    
    .info-label { font-size: 10px; text-transform: uppercase; letter-spacing: 0.8px; color: #8a8b92; font-weight: 700; margin-bottom: 4px; }
    .info-value { font-size: 14px; color: #f8f9fa; margin-bottom: 1.5rem; }
    
    .json-block { background-color: #0b0b14; border: 1px solid #2d2d3d; border-radius: 8px; padding: 1rem; color: #a8b2c1; font-family: monospace; font-size: 12px; max-height: 400px; overflow-y: auto; white-space: pre-wrap; }
    .border-dark-custom { border-color: #2d2d3d !important; }
</style>

<div class="container-fluid py-4">
    <div class="custom-card col-lg-10 mx-auto">
        <div class="custom-card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="m-0 fw-bold fs-5">🔍 Transaction Inspection</h4>
                <p class="m-0 mt-1" style="font-size: 12px; color: #9fa0a6;">Log Reference ID: #{{ $auditLog->id }}</p>
            </div>
            <a href="{{ route('audit-logs.index') }}" class="btn btn-outline-light btn-sm fw-semibold px-3">← Return to Trail</a>
        </div>
        
        <div class="card-body p-4 row">
            <div class="col-md-4 border-end border-dark-custom pe-md-4">
                <h6 class="mb-4 text-light fw-bold border-bottom border-dark-custom pb-2">Event Metadata</h6>
                
                <div class="info-label">Action Performed By</div>
                <div class="info-value fw-semibold text-info">{{ $auditLog->user_name }} <span class="text-muted fw-normal small">(ID: {{ $auditLog->user_id ?? 'System' }})</span></div>
                
                <div class="info-label">Action Classification</div>
                <div class="info-value">{{ $auditLog->action }}</div>

                <div class="info-label">Event Description</div>
                <div class="info-value text-light opacity-75">{{ $auditLog->description }}</div>

                <div class="info-label">System Timestamp</div>
                <div class="info-value font-monospace">{{ $auditLog->created_at->format('Y-m-d H:i:s T') }}</div>

                <div class="info-label">Target Module Model</div>
                <div class="info-value font-monospace text-light opacity-50">{{ $auditLog->model_type ?? 'N/A' }} <br> (ID: {{ $auditLog->model_id ?? 'N/A' }})</div>

                <div class="info-label">Network Tracking (IP)</div>
                <div class="info-value font-monospace text-light opacity-50">{{ $auditLog->ip_address ?? 'Unknown' }}</div>
            </div>

            

        </div>
    </div>
</div>
@endsection