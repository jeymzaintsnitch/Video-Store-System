@extends('layouts.app')

@section('title', 'Modify Inventory Asset #' . $tape->tape_number)

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body { background-color: #0f0f15; color: #e4e6eb; }
    .custom-card { border: 1px solid #2d2d3d; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.4); background: #161625; overflow: hidden; }
    .custom-card-header { background: #0b0b14; color: #fff; padding: 1.25rem 1.5rem; border-bottom: 1px solid #2d2d3d; }
    .custom-card-header h4 { margin: 0; font-weight: 600; font-size: 1.25rem; color: #f8f9fa; }
    .custom-card-header p { margin: 4px 0 0; font-size: 13px; color: #9fa0a6; }
    .form-label-custom { font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; color: #8a8b92; font-weight: 700; margin-bottom: 6px; }
    .form-control-custom { background-color: #222235 !important; border: 1px solid #3a3a52 !important; color: #f8f9fa !important; border-radius: 8px; padding: 10px 14px; }
    .form-control-custom:focus { background-color: #2a2a3f !important; border-color: #5a5b7a !important; color: #fff !important; box-shadow: none; }
    .text-danger-custom { color: #ff6b6b; font-size: 12px; margin-top: 4px; display: block; }
    .text-white-custom { color: #ffffff !important; background-color: #222235 !important; }
</style>

<div class="container-fluid py-4">
    <div class="custom-card col-lg-8 mx-auto">
        <div class="custom-card-header d-flex justify-content-between align-items-center">
            <div>
                <h4>📝 Edit Tape Properties</h4>
                <p>Updating configuration profiles for Unique Asset Token: {{ $tape->tape_number }}</p>
            </div>
            <!-- Top smart contextual return link -->
            <a href="{{ Str::contains(url()->previous(), route('tapes.show', $tape)) ? route('tapes.show', $tape) : route('tapes.index') }}" class="btn btn-outline-light btn-sm fw-semibold px-3">← Back</a>
        </div>
        
        <div class="card-body p-4">
            <form method="POST" action="{{ route('tapes.update', $tape) }}">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label for="tape_number" class="form-label-custom">Barcode / Tracking Number</label>
                        <input type="text" name="tape_number" id="tape_number" class="form-control form-control-custom @error('tape_number') is-invalid @enderror" value="{{ old('tape_number', $tape->tape_number) }}" required>
                        @error('tape_number') <span class="text-danger-custom">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="movie_id" class="form-label-custom">Link to Movie Master Profile</label>
                        <select name="movie_id" id="movie_id" class="form-select form-control-custom text-white-custom @error('movie_id') is-invalid @enderror" required>
                            @foreach($movies as $movie)
                                <option value="{{ $movie->id }}" class="text-white-custom" {{ old('movie_id', $tape->movie_id) == $movie->id ? 'selected' : '' }}>{{ $movie->title }} ({{ $movie->movie_id }})</option>
                            @endforeach
                        </select>
                        @error('movie_id') <span class="text-danger-custom">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label for="format" class="form-label-custom">Media Physical Format</label>
                        <select name="format" id="format" class="form-select form-control-custom text-white-custom @error('format') is-invalid @enderror" required>
                            <option value="VHS" class="text-white-custom" {{ old('format', $tape->format) == 'VHS' ? 'selected' : '' }}>VHS Tape</option>
                            <option value="Beta" class="text-white-custom" {{ old('format', $tape->format) == 'Beta' ? 'selected' : '' }}>Beta Tape</option>
                            <option value="DVD" class="text-white-custom" {{ old('format', $tape->format) == 'DVD' ? 'selected' : '' }}>DVD Digital Disk</option>
                        </select>
                        @error('format') <span class="text-danger-custom">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="shelf_location" class="form-label-custom">Warehouse Grid Shelf Location</label>
                        <input type="text" name="shelf_location" id="shelf_location" class="form-control form-control-custom @error('shelf_location') is-invalid @enderror" value="{{ old('shelf_location', $tape->shelf_location) }}">
                        @error('shelf_location') <span class="text-danger-custom">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="condition" class="form-label-custom">Current Asset Material Condition</label>
                        <select name="condition" id="condition" class="form-select form-control-custom text-white-custom @error('condition') is-invalid @enderror" required>
                            <option value="Good" class="text-white-custom" {{ old('condition', $tape->condition) == 'Good' ? 'selected' : '' }}>Good / Operational</option>
                            <option value="Fair" class="text-white-custom" {{ old('condition', $tape->condition) == 'Fair' ? 'selected' : '' }}>Fair / Minor Scratches</option>
                            <option value="Poor" class="text-white-custom" {{ old('condition', $tape->condition) == 'Poor' ? 'selected' : '' }}>Poor / Damage / Degrading</option>
                        </select>
                        @error('condition') <span class="text-danger-custom">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-secondary border-opacity-10">
                    <!-- Bottom context-aware routing override button -->
                    <a href="{{ Str::contains(url()->previous(), route('tapes.show', $tape)) ? route('tapes.show', $tape) : route('tapes.index') }}" class="btn btn-outline-danger px-4 fw-semibold">Abandon Edits</a>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold">Save Core Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection