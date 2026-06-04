 
@extends('layouts.app')

@section('title', 'Edit Invoice')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-pencil me-2"></i>Edit Invoice — {{ $invoice->invoice_number }}
    </div>
    <div class="card-body">
        <form action="{{ route('invoices.update', $invoice) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Customer <span class="text-danger">*</span></label>
                    <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror">
                        <option value="">-- Select Customer --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $invoice->customer_id) == $customer->id ? 'selected' : '' }}>
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
                            <option value="{{ $package->id }}" {{ old('package_id', $invoice->package_id) == $package->id ? 'selected' : '' }}>
                                {{ $package->name }} - Rs. {{ number_format($package->price) }}
                            </option>
                        @endforeach
                    </select>
                    @error('package_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Amount (Rs.) <span class="text-danger">*</span></label>
                    <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror"
                        value="{{ old('amount', $invoice->amount) }}" step="0.01">
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Discount (Rs.)</label>
                    <input type="number" name="discount" class="form-control"
                        value="{{ old('discount', $invoice->discount) }}" step="0.01">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tax (Rs.)</label>
                    <input type="number" name="tax" class="form-control"
                        value="{{ old('tax', $invoice->tax) }}" step="0.01">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Issue Date <span class="text-danger">*</span></label>
                    <input type="date" name="issue_date" class="form-control @error('issue_date') is-invalid @enderror"
                        value="{{ old('issue_date', $invoice->issue_date) }}">
                    @error('issue_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Due Date <span class="text-danger">*</span></label>
                    <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror"
                        value="{{ old('due_date', $invoice->due_date) }}">
                    @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="unpaid" {{ old('status', $invoice->status) == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="paid" {{ old('status', $invoice->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="partial" {{ old('status', $invoice->status) == 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="overdue" {{ old('status', $invoice->status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $invoice->notes) }}</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Update Invoice
                    </button>
                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary ms-2">
                        <i class="bi bi-x-lg"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection