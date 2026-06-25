@extends('layouts.app')

@section('title', 'Edit Movie Properties - ' . $movie->title)

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
    .form-control-custom:focus { background-color: #2a2a3f !important; border-color: #5c63ff !important; color: #fff !important; box-shadow: none; }
    .border-dark-custom { border-color: #2d2d3d !important; }
    .text-danger-custom { color: #ff6b6b; font-size: 12px; margin-top: 4px; display: block; }
    .text-white-custom { color: #ffffff !important; background-color: #222235 !important; }
    .form-check-input-custom { background-color: #222235; border: 1px solid #3a3a52; cursor: pointer; }
    .form-check-input-custom:checked { background-color: #0d6efd; border-color: #0d6efd; }
    .form-check-input-custom:focus { box-shadow: none; border-color: #5a5b7a; }
    .clickable-label { cursor: pointer; user-select: none; }

    .current-cover { width: 100%; max-height: 200px; object-fit: cover; border-radius: 10px; border: 1px solid #3a3a52; }
    .image-upload-zone { border: 2px dashed #3a3a52; border-radius: 10px; background: #0b0b14; padding: 16px; text-align: center; cursor: pointer; transition: border-color 0.2s; }
    .image-upload-zone:hover { border-color: #5c63ff; }
    #image-preview { max-width: 100%; max-height: 160px; border-radius: 8px; display: none; margin-top: 10px; object-fit: cover; }
</style>

<div class="container-fluid py-4">
    <div class="custom-card">
        <div class="custom-card-header d-flex justify-content-between align-items-center">
            <div>
                <h4>📝 Edit Movie Properties</h4>
                <p>Modify Asset Registry Entry for Catalog ID: <span class="text-info font-monospace">{{ $movie->movie_id }}</span></p>
            </div>
            <a href="{{ route('movies.show', $movie) }}" class="btn btn-outline-light btn-sm fw-semibold px-3">← Cancel & Return</a>
        </div>

        <div class="card-body p-4">
            @if ($errors->any())
                <div class="alert alert-danger border-0 mb-4 p-3 rounded" style="background-color: rgba(217,48,37,0.15); color: #f28b82;">
                    <p class="fw-bold mb-1" style="font-size:14px;">⚠️ Structural Update Blocked:</p>
                    <ul class="ps-3 mb-0" style="font-size:13px;">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('movies.update', $movie) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Left column --}}
                    <div class="col-md-8">
                        <h5 class="mb-4 pb-2 border-bottom border-dark-custom fw-semibold text-light">Core Asset Metadata</h5>

                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="movie_id" class="form-label-custom">Catalog ID String</label>
                                <input type="text" name="movie_id" id="movie_id" class="form-control form-control-custom @error('movie_id') is-invalid @enderror" value="{{ old('movie_id', $movie->movie_id) }}" required>
                                @error('movie_id')<span class="text-danger-custom">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label-custom">Movie Title</label>
                                <input type="text" name="title" id="title" class="form-control form-control-custom @error('title') is-invalid @enderror" value="{{ old('title', $movie->title) }}" required>
                                @error('title')<span class="text-danger-custom">{{ $message }}</span>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label-custom">Genre / Classification</label>
                                <select name="category_id" id="category_id" class="form-select form-control-custom text-white-custom @error('category_id') is-invalid @enderror" required>
                                    <option value="" class="text-white-custom">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" class="text-white-custom" {{ old('category_id', $movie->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')<span class="text-danger-custom">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="director" class="form-label-custom">Director / Creator</label>
                                <input type="text" name="director" id="director" class="form-control form-control-custom @error('director') is-invalid @enderror" value="{{ old('director', $movie->director) }}" required>
                                @error('director')<span class="text-danger-custom">{{ $message }}</span>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="year" class="form-label-custom">Production Year</label>
                                <input type="text" name="year" id="year" class="form-control form-control-custom @error('year') is-invalid @enderror" value="{{ old('year', $movie->year) }}" required>
                                @error('year')<span class="text-danger-custom">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="description" class="form-label-custom">Description / Logline Summary</label>
                                <textarea name="description" id="description" rows="4" class="form-control form-control-custom @error('description') is-invalid @enderror" style="line-height:1.6;">{{ old('description', $movie->description) }}</textarea>
                                @error('description')<span class="text-danger-custom">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        {{-- Image Update --}}
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label-custom">Movie Cover Image</label>
                                @if($movie->image)
                                    <div class="mb-2">
                                        <p class="text-muted small mb-1">Current cover:</p>
                                        <img src="{{ asset('storage/' . $movie->image) }}" class="current-cover" alt="{{ $movie->title }}">
                                    </div>
                                @endif
                                <div class="image-upload-zone mt-2" onclick="document.getElementById('image-input').click()">
                                    <span style="font-size:22px;">🔄</span>
                                    <p class="text-muted small mt-1 mb-0">{{ $movie->image ? 'Click to replace the cover image' : 'Click to upload a cover image' }} (JPEG, PNG, WebP – max 2MB)</p>
                                    <img id="image-preview" src="" alt="New Preview">
                                </div>
                                <input type="file" id="image-input" name="image" accept="image/*" class="d-none" onchange="previewImage(event)">
                                @error('image')<span class="text-danger-custom">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Right column: cast --}}
                    <div class="col-md-4 border-start border-dark-custom ps-md-4">
                        <h5 class="mb-4 pb-2 border-bottom border-dark-custom fw-semibold text-light">Linked Production Cast</h5>
                        <div class="mb-4">
                            <label class="form-label-custom d-block mb-2">Select Assigned Personnel</label>
                            <div style="max-height: 320px; overflow-y: auto; background-color: #0b0b14; border: 1px solid #2d2d3d; border-radius: 8px; padding: 12px;">
                                @foreach($actors as $actor)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input form-check-input-custom" type="checkbox" name="actor_ids[]" value="{{ $actor->id }}" id="actor_id_{{ $actor->id }}"
                                            {{ in_array($actor->id, old('actor_ids', $movie->actors->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <label class="form-check-label text-light small clickable-label" for="actor_id_{{ $actor->id }}">
                                            👤 {{ $actor->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('actor_ids')<span class="text-danger-custom">{{ $message }}</span>@enderror
                        </div>

                        <h5 class="mb-3 pb-2 border-bottom border-dark-custom fw-semibold text-light">System Actions</h5>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-sm py-2 fw-semibold">Save Structural Changes</button>
                            <a href="{{ route('movies.show', $movie) }}" class="btn btn-outline-danger btn-sm py-2 fw-semibold">Abandon Edits</a>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('image-preview');
    const file = event.target.files[0];
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    }
}
</script>
@endsection