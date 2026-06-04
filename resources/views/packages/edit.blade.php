@extends('layouts.app')

@section('title', 'Edit Package')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-pencil me-2"></i>Edit Package — {{ $package->name }}
    </div>
    <div class="card-body">
        <form action="{{ route('packages.update', $package) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Package Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $package->name) }}">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Speed <span class="text-danger">*</span></label>
                    <input type="text" name="speed" class="form-control @error('speed') is-invalid @enderror"
                        value="{{ old('speed', $package->speed) }}">
                    @error('speed')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Price (Rs.) <span class="text-danger">*</span></label>
                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                        value="{{ old('price', $package->price) }}">
                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Validity <span class="text-danger">*</span></label>
                    <select name="validity" class="form-select @error('validity') is-invalid @enderror">
                        <option value="monthly" {{ old('validity', $package->validity) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="quarterly" {{ old('validity', $package->validity) == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                        <option value="yearly" {{ old('validity', $package->validity) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                    @error('validity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror">
                        <option value="fiber" {{ old('type', $package->type) == 'fiber' ? 'selected' : '' }}>Fiber</option>
                        <option value="wireless" {{ old('type', $package->type) == 'wireless' ? 'selected' : '' }}>Wireless</option>
                        <option value="dsl" {{ old('type', $package->type) == 'dsl' ? 'selected' : '' }}>DSL</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="1" {{ $package->is_active ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$package->is_active ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description', $package->description) }}</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Update Package
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