 
@extends('layouts.app')

@section('title', 'Add Connection')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-plug me-2"></i>Add New Connection
    </div>
    <div class="card-body">
        <form action="{{ route('connections.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Customer <span class="text-danger">*</span></label>
                    <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror">
                        <option value="">-- Select Customer --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} - {{ $customer->phone }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Package <span class="text-danger">*</span></label>
                    <select name="package_id" class="form-select @error('package_id') is-invalid @enderror">
                        <option value="">-- Select Package --</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                {{ $package->name }} - Rs. {{ number_format($package->price) }}
                            </option>
                        @endforeach
                    </select>
                    @error('package_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Area <span class="text-danger">*</span></label>
                    <select name="area_id" class="form-select @error('area_id') is-invalid @enderror">
                        <option value="">-- Select Area --</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                {{ $area->area_name }} - {{ $area->city }}
                            </option>
                        @endforeach
                    </select>
                    @error('area_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Connection Type <span class="text-danger">*</span></label>
                    <select name="connection_type" class="form-select @error('connection_type') is-invalid @enderror">
                        <option value="fiber" {{ old('connection_type') == 'fiber' ? 'selected' : '' }}>Fiber</option>
                        <option value="wireless" {{ old('connection_type') == 'wireless' ? 'selected' : '' }}>Wireless</option>
                        <option value="dsl" {{ old('connection_type') == 'dsl' ? 'selected' : '' }}>DSL</option>
                    </select>
                    @error('connection_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">IP Address</label>
                    <input type="text" name="ip_address" class="form-control"
                        value="{{ old('ip_address') }}" placeholder="e.g. 192.168.1.100">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">MAC Address</label>
                    <input type="text" name="mac_address" class="form-control"
                        value="{{ old('mac_address') }}" placeholder="e.g. 00:1A:2B:3C:4D:5E">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">PPPoE Username</label>
                    <input type="text" name="username" class="form-control"
                        value="{{ old('username') }}" placeholder="PPPoE username">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">PPPoE Password</label>
                    <input type="text" name="password" class="form-control"
                        value="{{ old('password') }}" placeholder="PPPoE password">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Installation Date <span class="text-danger">*</span></label>
                    <input type="date" name="installation_date" class="form-control @error('installation_date') is-invalid @enderror"
                        value="{{ old('installation_date', date('Y-m-d')) }}">
                    @error('installation_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Technician</label>
                    <select name="technician_id" class="form-select">
                        <option value="">-- Select Technician --</option>
                        @foreach($technicians as $technician)
                            <option value="{{ $technician->id }}" {{ old('technician_id') == $technician->id ? 'selected' : '' }}>
                                {{ $technician->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="terminated" {{ old('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Save Connection
                    </button>
                    <a href="{{ route('connections.index') }}" class="btn btn-secondary ms-2">
                        <i class="bi bi-x-lg"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection