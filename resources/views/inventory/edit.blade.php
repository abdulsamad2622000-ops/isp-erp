 
@extends('layouts.app')

@section('title', 'Edit Inventory Item')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-pencil me-2"></i>Edit Item — {{ $inventory->item_name }}
    </div>
    <div class="card-body">
        <form action="{{ route('inventory.update', $inventory) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Item Name <span class="text-danger">*</span></label>
                    <input type="text" name="item_name" class="form-control @error('item_name') is-invalid @enderror"
                        value="{{ old('item_name', $inventory->item_name) }}">
                    @error('item_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Model</label>
                    <input type="text" name="model" class="form-control"
                        value="{{ old('model', $inventory->model) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror">
                        <option value="router" {{ old('category', $inventory->category) == 'router' ? 'selected' : '' }}>Router</option>
                        <option value="onu" {{ old('category', $inventory->category) == 'onu' ? 'selected' : '' }}>ONU</option>
                        <option value="cable" {{ old('category', $inventory->category) == 'cable' ? 'selected' : '' }}>Cable</option>
                        <option value="switch" {{ old('category', $inventory->category) == 'switch' ? 'selected' : '' }}>Switch</option>
                        <option value="splitter" {{ old('category', $inventory->category) == 'splitter' ? 'selected' : '' }}>Splitter</option>
                        <option value="other" {{ old('category', $inventory->category) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Total Stock <span class="text-danger">*</span></label>
                    <input type="number" name="total_stock" class="form-control @error('total_stock') is-invalid @enderror"
                        value="{{ old('total_stock', $inventory->total_stock) }}" min="0">
                    @error('total_stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Available Stock</label>
                    <input type="number" name="available_stock" class="form-control"
                        value="{{ old('available_stock', $inventory->available_stock) }}" min="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Assigned Stock</label>
                    <input type="number" name="assigned_stock" class="form-control"
                        value="{{ old('assigned_stock', $inventory->assigned_stock) }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Unit Price (Rs.)</label>
                    <input type="number" name="unit_price" class="form-control"
                        value="{{ old('unit_price', $inventory->unit_price) }}" step="0.01">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Supplier</label>
                    <input type="text" name="supplier" class="form-control"
                        value="{{ old('supplier', $inventory->supplier) }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Update Item
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