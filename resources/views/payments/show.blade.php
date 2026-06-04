 
@extends('layouts.app')

@section('title', 'Payment Detail')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-cash-stack me-2"></i>Payment Detail</span>
        <div>
            <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning btn-sm text-white">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('payments.index') }}" class="btn btn-secondary btn-sm ms-2">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td class="text-muted" style="width:40%">Customer</td>
                        <td><strong>{{ $payment->customer->name ?? 'N/A' }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Invoice #</td>
                        <td>{{ $payment->invoice->invoice_number ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Amount Paid</td>
                        <td><strong class="text-success">Rs. {{ number_format($payment->amount_paid) }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Payment Date</td>
                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td class="text-muted" style="width:40%">Method</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Transaction ID</td>
                        <td>{{ $payment->transaction_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Received By</td>
                        <td>{{ $payment->receivedBy->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Notes</td>
                        <td>{{ $payment->notes ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection