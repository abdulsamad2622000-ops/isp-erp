@extends('layouts.app')

@section('title', 'Add Area')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-geo-alt me-2"></i>Add New Area
    </div>
    <div class="card-body">
        <form action="{{ route('areas.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">City <span class="text-danger">*</span></label>
                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                        value="{{ old('city') }}" placeholder="e.g. Karachi">
                    @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Area Name <span class="text-danger">*</span></label>
                    <input type="text" name="area_name" class="form-control @error('area_name') is-invalid @enderror"
                        value="{{ old('area_name') }}" placeholder="e.g. Gulshan-e-Iqbal">
                    @error('area_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Sub Area</label>
                    <input type="text" name="sub_area" class="form-control"
                        value="{{ old('sub_area') }}" placeholder="e.g. Block 10">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Coverage Details</label>
                    <textarea name="coverage_details" class="form-control" rows="2"
                        placeholder="Enter coverage details">{{ old('coverage_details') }}</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Save Area
                    </button>
                    <a href="{{ route('areas.index') }}" class="btn btn-secondary ms-2">
                        <i class="bi bi-x-lg"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection