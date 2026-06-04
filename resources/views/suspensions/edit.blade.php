 
@extends('layouts.app')

@section('title', 'Edit Suspension')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-pencil me-2"></i>Edit Suspension
    </div>
    <div class="card-body">
        <form action="{{ route('suspensions.update', $suspension) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Customer <span class="text-danger">*</span></label>
                    <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror">
                        <option value="">-- Select Customer --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $suspension->customer_id) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} - {{ $customer->phone }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Connection <span class="text-danger">*</span></label>
                    <select name="connection_id" class="form-select @error('connection_id') is-invalid @enderror">
                        <option value="">-- Select Connection --</option>
                        @foreach($connections as $connection)
                            <option value="{{ $connection->id }}" {{ old('connection_id', $suspension->connection_id) == $connection->id ? 'selected' : '' }}>
                                {{ $connection->customer->name ?? '' }} - {{ $connection->ip_address ?? 'No IP' }}
                            </option>
                        @endforeach
                    </select>
                    @error('connection_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Reason <span class="text-danger">*</span></label>
                    <select name="reason" class="form-select @error('reason') is-invalid @enderror">
                        <option value="non_payment" {{ old('reason', $suspension->reason) == 'non_payment' ? 'selected' : '' }}>Non Payment</option>
                        <option value="request" {{ old('reason', $suspension->reason) == 'request' ? 'selected' : '' }}>Customer Request</option>
                        <option value="violation" {{ old('reason', $suspension->reason) == 'violation' ? 'selected' : '' }}>Violation</option>
                        <option value="other" {{ old('reason', $suspension->reason) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="suspended" {{ old('status', $suspension->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="reconnected" {{ old('status', $suspension->status) == 'reconnected' ? 'selected' : '' }}>Reconnected</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Suspension Date <span class="text-danger">*</span></label>
                    <input type="date" name="suspension_date" class="form-control @error('suspension_date') is-invalid @enderror"
                        value="{{ old('suspension_date', $suspension->suspension_date) }}">
                    @error('suspension_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Reconnection Date</label>
                    <input type="date" name="reconnection_date" class="form-control"
                        value="{{ old('reconnection_date', $suspension->reconnection_date) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Actioned By</label>
                    <select name="actioned_by" class="form-select">
                        <option value="">-- Select Staff --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('actioned_by', $suspension->actioned_by) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Notes</label>
                    <textarea name="notes" class="form-control" rows="1">{{ old('notes', $suspension->notes) }}</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Update Suspension
                    </button>
                    <a href="{{ route('suspensions.index') }}" class="btn btn-secondary ms-2">
                        <i class="bi bi-x-lg"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection