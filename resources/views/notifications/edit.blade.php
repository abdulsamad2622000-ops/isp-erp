@extends('layouts.app')

@section('title', 'Edit Notification')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-pencil me-2"></i>Edit Notification
    </div>
    <div class="card-body">
        <form action="{{ route('notifications.update', $notification) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Customer</label>
                    <select name="customer_id" class="form-select">
                        <option value="">-- All Customers --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $notification->customer_id) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} - {{ $customer->phone }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title', $notification->title) }}">
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror">
                        <option value="bill_reminder" {{ old('type', $notification->type) == 'bill_reminder' ? 'selected' : '' }}>Bill Reminder</option>
                        <option value="suspension_warning" {{ old('type', $notification->type) == 'suspension_warning' ? 'selected' : '' }}>Suspension Warning</option>
                        <option value="promotion" {{ old('type', $notification->type) == 'promotion' ? 'selected' : '' }}>Promotion</option>
                        <option value="general" {{ old('type', $notification->type) == 'general' ? 'selected' : '' }}>General</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Channel <span class="text-danger">*</span></label>
                    <select name="channel" class="form-select @error('channel') is-invalid @enderror">
                        <option value="sms" {{ old('channel', $notification->channel) == 'sms' ? 'selected' : '' }}>SMS</option>
                        <option value="email" {{ old('channel', $notification->channel) == 'email' ? 'selected' : '' }}>Email</option>
                        <option value="whatsapp" {{ old('channel', $notification->channel) == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                    </select>
                    @error('channel')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                    <textarea name="message" class="form-control @error('message') is-invalid @enderror"
                        rows="3">{{ old('message', $notification->message) }}</textarea>
                    @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Update Notification
                    </button>
                    <a href="{{ route('notifications.index') }}" class="btn btn-secondary ms-2">
                        <i class="bi bi-x-lg"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection