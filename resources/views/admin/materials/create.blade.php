@extends('layouts.app')

@section('title', 'Add New Material')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add New Material</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.materials') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Materials
        </a>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.materials.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Material Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="sw_name" class="form-label">Swahili Name</label>
                        <input type="text" class="form-control @error('sw_name') is-invalid @enderror" id="sw_name" name="sw_name" value="{{ old('sw_name') }}">
                        @error('sw_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="sw_description" class="form-label">Swahili Description</label>
                        <textarea class="form-control @error('sw_description') is-invalid @enderror" id="sw_description" name="sw_description" rows="4">{{ old('sw_description') }}</textarea>
                        @error('sw_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Price (TZS) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" min="1000" step="0.01" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Minimum price is TZS 1,000</div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Material Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB</div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.materials') }}" class="btn btn-secondary me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Material
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Material Guidelines</h5>
            </div>
            <div class="card-body">
                <h6>Material Information</h6>
                <ul class="small">
                    <li>Provide clear, descriptive names</li>
                    <li>Include detailed descriptions</li>
                    <li>Set competitive prices</li>
                    <li>Upload high-quality images</li>
                </ul>

                <h6>Purchase Options</h6>
                <p class="small">Users can choose between:</p>
                <ul class="small">
                    <li><strong>Direct Purchase:</strong> Full payment upfront</li>
                    <li><strong>Lipa Kidogo:</strong> Installment payments</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
