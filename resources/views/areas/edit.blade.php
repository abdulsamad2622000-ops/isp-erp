@extends('layouts.app')

@section('title', 'Edit Area')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-pencil me-2"></i>Edit Area — {{ $area->area_name }}
    </div>
    <div class="card-body">
        <form action="{{ route('areas.update', $area) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">City <span class="text-danger">*</span></label>
                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                        value="{{ old('city', $area->city) }}">
                    @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Area Name <span class="text-danger">*</span></label>
                    <input type="text" name="area_name" class="form-control @error('area_name') is-invalid @enderror"
                        value="{{ old('area_name', $area->area_name) }}">
                    @error('area_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Sub Area</label>
                    <input type="text" name="sub_area" class="form-control"
                        value="{{ old('sub_area', $area->sub_area) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="1" {{ $area->is_active ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$area->is_active ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Coverage Details</label>
                    <textarea name="coverage_details" class="form-control" rows="2">{{ old('coverage_details', $area->coverage_details) }}</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Update Area
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