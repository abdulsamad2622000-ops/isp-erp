 
@extends('layouts.app')

@section('title', 'Invoice Detail')

@section('content')
<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-receipt me-2"></i>{{ $invoice->invoice_number }}</span>
                <div>
                    <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-warning btn-sm text-white">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary btn-sm ms-2">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td class="text-muted">Customer</td>
                                <td><strong>{{ $invoice->customer->name ?? 'N/A' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Phone</td>
                                <td>{{ $invoice->customer->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Package</td>
                                <td>{{ $invoice->package->name ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td class="text-muted">Issue Date</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->issue_date)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Due Date</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status</td>
                                <td>
                                    <span class="badge bg-{{ $invoice->status == 'paid' ? 'success' : ($invoice->status == 'overdue' ? 'danger' : ($invoice->status == 'partial' ? 'info' : 'warning')) }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="row justify-content-end">
                    <div class="col-md-4">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td class="text-muted">Amount</td>
                                <td class="text-end">Rs. {{ number_format($invoice->amount) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Discount</td>
                                <td class="text-end text-danger">- Rs. {{ number_format($invoice->discount) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tax</td>
                                <td class="text-end">+ Rs. {{ number_format($invoice->tax) }}</td>
                            </tr>
                            <tr class="border-top">
                                <td><strong>Total</strong></td>
                                <td class="text-end"><strong>Rs. {{ number_format($invoice->total_amount) }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
                @if($invoice->notes)
                <div class="alert alert-light mt-2">
                    <strong>Notes:</strong> {{ $invoice->notes }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Payments -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-cash-stack me-2"></i>Payments</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Amount</th>
                            <th>Method</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoice->payments as $payment)
                        <tr>
                            <td class="ps-3">Rs. {{ number_format($payment->amount_paid) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">No payments yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection