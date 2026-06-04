 
@extends('layouts.app')

@section('title', 'Add Inventory Item')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-archive me-2"></i>Add New Inventory Item
    </div>
    <div class="card-body">
        <form action="{{ route('inventory.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Item Name <span class="text-danger">*</span></label>
                    <input type="text" name="item_name" class="form-control @error('item_name') is-invalid @enderror"
                        value="{{ old('item_name') }}" placeholder="e.g. TP-Link Router">
                    @error('item_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Model</label>
                    <input type="text" name="model" class="form-control"
                        value="{{ old('model') }}" placeholder="e.g. TL-WR840N">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror">
                        <option value="router" {{ old('category') == 'router' ? 'selected' : '' }}>Router</option>
                        <option value="onu" {{ old('category') == 'onu' ? 'selected' : '' }}>ONU</option>
                        <option value="cable" {{ old('category') == 'cable' ? 'selected' : '' }}>Cable</option>
                        <option value="switch" {{ old('category') == 'switch' ? 'selected' : '' }}>Switch</option>
                        <option value="splitter" {{ old('category') == 'splitter' ? 'selected' : '' }}>Splitter</option>
                        <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Total Stock <span class="text-danger">*</span></label>
                    <input type="number" name="total_stock" class="form-control @error('total_stock') is-invalid @enderror"
                        value="{{ old('total_stock', 0) }}" min="0">
                    @error('total_stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Unit Price (Rs.)</label>
                    <input type="number" name="unit_price" class="form-control"
                        value="{{ old('unit_price') }}" placeholder="0.00" step="0.01">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Supplier</label>
                    <input type="text" name="supplier" class="form-control"
                        value="{{ old('supplier') }}" placeholder="Supplier name">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Save Item
                    </button>
                    <a href="{{ route('inventory.index') }}" class="btn btn-secondary ms-2">
                        <i class="bi bi-x-lg"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection