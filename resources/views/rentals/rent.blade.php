@extends('layouts.app')

@section('title', 'Rent Tape - ' . $tape->tape_number)

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body { background-color: #0f0f15; color: #e4e6eb; }
    .custom-card { border: 1px solid #2d2d3d; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.4); background: #161625; overflow: hidden; max-width: 600px; margin: 0 auto; }
    .custom-card-header { background: #0b0b14; color: #fff; padding: 1.25rem 1.5rem; border-bottom: 1px solid #2d2d3d; }
    .custom-card-header h4 { margin: 0; font-weight: 600; font-size: 1.25rem; color: #f8f9fa; }
    .custom-card-header p { margin: 4px 0 0; font-size: 13px; color: #9fa0a6; }
    .form-label-custom { font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; color: #8a8b92; font-weight: 700; margin-bottom: 6px; }
    .form-control-custom { background-color: #222235 !important; border: 1px solid #3a3a52 !important; color: #ffffff !important; border-radius: 8px; padding: 10px 14px; }
    .form-control-custom:focus { background-color: #2a2a3f !important; border-color: #5c63ff !important; color: #fff !important; box-shadow: none; }
    .form-control-custom::placeholder { color: #a1a1aa !important; }
    .text-danger-custom { color: #ff6b6b; font-size: 12px; margin-top: 4px; display: block; }

    .tape-info-box { background: #0b0b14; border: 1px solid #2d2d3d; border-radius: 10px; padding: 16px 20px; margin-bottom: 24px; }
    .tape-info-box .info-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px; }
    .tape-info-box .info-label { color: #8a8b92; }
    .tape-info-box .info-value { color: #e8eaed; font-weight: 500; }
</style>

<div class="container-fluid py-4">
    <div class="custom-card">
        <div class="custom-card-header d-flex justify-content-between align-items-center">
            <div>
                <h4>📼 Rent a Tape</h4>
                <p>Issue a tape to a customer and set a return due date</p>
            </div>
            <a href="{{ route('movies.show', $tape->movie) }}" class="btn btn-outline-light btn-sm fw-semibold px-3">← Back to Movie</a>
        </div>

        <div class="card-body p-4">
            {{-- Tape info --}}
            <div class="tape-info-box">
                <div class="info-row">
                    <span class="info-label">Tape Number</span>
                    <span class="info-value font-monospace text-info">{{ $tape->tape_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Movie Title</span>
                    <span class="info-value">{{ $tape->movie->title }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Format</span>
                    <span class="info-value">{{ $tape->format }}</span>
                </div>
                <div class="info-row mb-0">
                    <span class="info-label">Condition</span>
                    <span class="info-value">{{ $tape->condition }}</span>
                </div>
            </div>


            <form method="POST" action="{{ route('rentals.store', $tape) }}">
                @csrf

                <div class="mb-4">
                    <label for="rented_by" class="form-label-custom">Customer Name</label>
                    <input type="text" name="rented_by" id="rented_by"
                        class="form-control form-control-custom @error('rented_by') is-invalid @enderror"
                        value="{{ old('rented_by') }}" placeholder="Enter customer full name" required>
                    @error('rented_by')<span class="text-danger-custom">{{ $message }}</span>@enderror
                </div>

                <div class="mb-4">
                    <label for="due_date" class="form-label-custom">Due Return Date</label>
                    <input type="date" name="due_date" id="due_date"
                        class="form-control form-control-custom @error('due_date') is-invalid @enderror"
                        value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}"
                        min="{{ now()->addDay()->format('Y-m-d') }}" required>
                    @error('due_date')<span class="text-danger-custom">{{ $message }}</span>@enderror
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary py-2 fw-semibold">Confirm Rental</button>
                    <a href="{{ route('movies.show', $tape->movie) }}" class="btn btn-outline-danger py-2 fw-semibold">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
