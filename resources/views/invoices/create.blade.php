 
@extends('layouts.app')

@section('title', 'Create Invoice')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-receipt me-2"></i>Create New Invoice
    </div>
    <div class="card-body">
        <form action="{{ route('invoices.store') }}" method="POST">
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
                    <select name="package_id" id="package_id" class="form-select @error('package_id') is-invalid @enderror">
                        <option value="">-- Select Package --</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" data-price="{{ $package->price }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                {{ $package->name }} - Rs. {{ number_format($package->price) }}
                            </option>
                        @endforeach
                    </select>
                    @error('package_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Amount (Rs.) <span class="text-danger">*</span></label>
                    <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror"
                        value="{{ old('amount') }}" placeholder="0.00" step="0.01">
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Discount (Rs.)</label>
                    <input type="number" name="discount" id="discount" class="form-control"
                        value="{{ old('discount', 0) }}" placeholder="0.00" step="0.01">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tax (Rs.)</label>
                    <input type="number" name="tax" id="tax" class="form-control"
                        value="{{ old('tax', 0) }}" placeholder="0.00" step="0.01">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Total Amount</label>
                    <input type="text" id="total_display" class="form-control bg-light" readonly placeholder="0.00">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Issue Date <span class="text-danger">*</span></label>
                    <input type="date" name="issue_date" class="form-control @error('issue_date') is-invalid @enderror"
                        value="{{ old('issue_date', date('Y-m-d')) }}">
                    @error('issue_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Due Date <span class="text-danger">*</span></label>
                    <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror"
                        value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}">
                    @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="unpaid" {{ old('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="partial" {{ old('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="overdue" {{ old('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Notes</label>
                    <textarea name="notes" class="form-control" rows="1"
                        placeholder="Optional notes">{{ old('notes') }}</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Create Invoice
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

@section('scripts')
<script>
    // Auto fill amount from package price
    document.getElementById('package_id').addEventListener('change', function() {
        const price = this.options[this.selectedIndex].dataset.price;
        if (price) {
            document.getElementById('amount').value = price;
            calculateTotal();
        }
    });

    // Calculate total
    function calculateTotal() {
        const amount   = parseFloat(document.getElementById('amount').value) || 0;
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const tax      = parseFloat(document.getElementById('tax').value) || 0;
        const total    = amount - discount + tax;
        document.getElementById('total_display').value = 'Rs. ' + total.toLocaleString();
    }

    document.getElementById('amount').addEventListener('input', calculateTotal);
    document.getElementById('discount').addEventListener('input', calculateTotal);
    document.getElementById('tax').addEventListener('input', calculateTotal);
</script>
@endsection