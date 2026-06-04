 
@extends('layouts.app')

@section('title', 'Payments')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-cash-stack me-2"></i>All Payments</span>
        <a href="{{ route('payments.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Record Payment
        </a>
    </div>
    <div class="card-body p-0"><div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-3">#</th>
                    <th>Customer</th>
                    <th>Invoice #</th>
                    <th>Amount Paid</th>
                    <th>Method</th>
                    <th>Transaction ID</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td class="ps-3">{{ $loop->iteration }}</td>
                    <td>{{ $payment->customer->name ?? 'N/A' }}</td>
                    <td>{{ $payment->invoice->invoice_number ?? 'N/A' }}</td>
                    <td><strong>Rs. {{ number_format($payment->amount_paid) }}</strong></td>
                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                    <td>{{ $payment->transaction_id ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-info text-white">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('payments.edit', $payment) }}" class="btn btn-sm btn-warning text-white">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No payments found</td>
                </tr>
                @endforelse
            </tbody>
        </table></div></div>
    @if($payments->hasPages())
    <div class="card-footer">
        {{ $payments->links() }}
    </div>
    @endif
</div>
@endsection