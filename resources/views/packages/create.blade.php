 
@extends('layouts.app')

@section('title', 'Add Package')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-box me-2"></i>Add New Package
    </div>
    <div class="card-body">
        <form action="{{ route('packages.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Package Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="e.g. Basic 10MB">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Speed <span class="text-danger">*</span></label>
                    <input type="text" name="speed" class="form-control @error('speed') is-invalid @enderror"
                        value="{{ old('speed') }}" placeholder="e.g. 10 Mbps">
                    @error('speed')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Price (Rs.) <span class="text-danger">*</span></label>
                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                        value="{{ old('price') }}" placeholder="e.g. 1500">
                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Validity <span class="text-danger">*</span></label>
                    <select name="validity" class="form-select @error('validity') is-invalid @enderror">
                        <option value="monthly" {{ old('validity') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="quarterly" {{ old('validity') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                        <option value="yearly" {{ old('validity') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                    @error('validity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror">
                        <option value="fiber" {{ old('type') == 'fiber' ? 'selected' : '' }}>Fiber</option>
                        <option value="wireless" {{ old('type') == 'wireless' ? 'selected' : '' }}>Wireless</option>
                        <option value="dsl" {{ old('type') == 'dsl' ? 'selected' : '' }}>DSL</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="2"
                        placeholder="Package description (optional)">{{ old('description') }}</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Save Package
                    </button>
                    <a href="{{ route('packages.index') }}" class="btn btn-secondary ms-2">
                        <i class="bi bi-x-lg"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection