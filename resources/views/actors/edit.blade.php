@extends('layouts.app')

@section('title', 'Modify Actor Profile - ' . $actor->name)

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body { background-color: #0f0f15; color: #e4e6eb; }
    .custom-card { border: 1px solid #2d2d3d; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.4); background: #161625; overflow: hidden; }
    .custom-card-header { background: #0b0b14; color: #fff; padding: 1.25rem 1.5rem; border-bottom: 1px solid #2d2d3d; }
    .custom-card-header h4 { margin: 0; font-weight: 600; font-size: 1.25rem; color: #f8f9fa; }
    .custom-card-header p { margin: 4px 0 0; font-size: 13px; color: #9fa0a6; }
    
    .form-label-custom { font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; color: #8a8b92; font-weight: 700; margin-bottom: 6px; }
    
    .form-control-custom { background-color: #222235 !important; border: 1px solid #3a3a52 !important; color: #ffffff !important; border-radius: 8px; padding: 10px 14px; }
    .form-control-custom:focus { background-color: #2a2a3f !important; border-color: #5c63ff !important; color: #fff !important; box-shadow: none; }
    
    .text-danger-custom { color: #ff6b6b; font-size: 12px; margin-top: 4px; display: block; }
</style>

<div class="container-fluid py-4">
    <div class="custom-card col-lg-6 mx-auto">
        <div class="custom-card-header d-flex justify-content-between align-items-center">
            <div>
                <h4>📝 Update Talent Profile</h4>
                <p>Modify biographical data for: {{ $actor->name }}</p>
            </div>
            <a href="{{ Str::contains(url()->previous(), route('actors.show', $actor)) ? route('actors.show', $actor) : route('actors.index') }}" class="btn btn-outline-light btn-sm fw-semibold px-3">← Cancel</a>
        </div>
        
        <div class="card-body p-4">
            <form method="POST" action="{{ route('actors.update', $actor) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="form-label-custom">Actor Full Name / Stage Name</label>
                    <input type="text" name="name" id="name" class="form-control form-control-custom @error('name') is-invalid @enderror" value="{{ old('name', $actor->name) }}" required>
                    @error('name') <span class="text-danger-custom">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="bio" class="form-label-custom">Biographical Background Profile</label>
                    <textarea name="bio" id="bio" rows="6" class="form-control form-control-custom @error('bio') is-invalid @enderror">{{ old('bio', $actor->bio) }}</textarea>
                    @error('bio') <span class="text-danger-custom">{{ $message }}</span> @enderror
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-secondary border-opacity-10">
                    <a href="{{ Str::contains(url()->previous(), route('actors.show', $actor)) ? route('actors.show', $actor) : route('actors.index') }}" class="btn btn-outline-danger px-4 fw-semibold">Abandon Edits</a>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold">Save Profile Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection