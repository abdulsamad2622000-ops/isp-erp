 
@extends('layouts.app')

@section('title', 'Record Payment')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-cash-stack me-2"></i>Record New Payment
    </div>
    <div class="card-body">
        <form action="{{ route('payments.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Customer <span class="text-danger">*</span></label>
                    <select name="customer_id" id="customer_id" class="form-select @error('customer_id') is-invalid @enderror">
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
                    <label class="form-label fw-semibold">Invoice <span class="text-danger">*</span></label>
                    <select name="invoice_id" id="invoice_id" class="form-select @error('invoice_id') is-invalid @enderror">
                        <option value="">-- Select Invoice --</option>
                        @foreach($invoices as $invoice)
                            <option value="{{ $invoice->id }}"
                                data-customer="{{ $invoice->customer_id }}"
                                data-amount="{{ $invoice->total_amount }}"
                                {{ old('invoice_id') == $invoice->id ? 'selected' : '' }}>
                                {{ $invoice->invoice_number }} - Rs. {{ number_format($invoice->total_amount) }} ({{ $invoice->customer->name ?? '' }})
                            </option>
                        @endforeach
                    </select>
                    @error('invoice_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Amount Paid (Rs.) <span class="text-danger">*</span></label>
                    <input type="number" name="amount_paid" id="amount_paid" class="form-control @error('amount_paid') is-invalid @enderror"
                        value="{{ old('amount_paid') }}" placeholder="0.00" step="0.01">
                    @error('amount_paid')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Payment Date <span class="text-danger">*</span></label>
                    <input type="date" name="payment_date" class="form-control @error('payment_date') is-invalid @enderror"
                        value="{{ old('payment_date', date('Y-m-d')) }}">
                    @error('payment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Payment Method <span class="text-danger">*</span></label>
                    <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror">
                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="easypaisa" {{ old('payment_method') == 'easypaisa' ? 'selected' : '' }}>EasyPaisa</option>
                        <option value="jazzcash" {{ old('payment_method') == 'jazzcash' ? 'selected' : '' }}>JazzCash</option>
                        <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                    </select>
                    @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Transaction ID</label>
                    <input type="text" name="transaction_id" class="form-control"
                        value="{{ old('transaction_id') }}" placeholder="Optional transaction ID">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Received By</label>
                    <select name="received_by" class="form-select">
                        <option value="">-- Select Staff --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('received_by') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Notes</label>
                    <textarea name="notes" class="form-control" rows="1"
                        placeholder="Optional notes">{{ old('notes') }}</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Record Payment
                    </button>
                    <a href="{{ route('payments.index') }}" class="btn btn-secondary ms-2">
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
    // Auto fill amount from invoice
    document.getElementById('invoice_id').addEventListener('change', function() {
        const amount = this.options[this.selectedIndex].dataset.amount;
        if (amount) {
            document.getElementById('amount_paid').value = amount;
        }
    });
</script>
@endsection