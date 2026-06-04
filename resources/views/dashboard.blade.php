 
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<!-- Stats Cards Row 1 -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#e94560;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-people text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Total Customers</div>
                    <div class="fw-bold fs-4">{{ $total_customers }}</div>
                    <div style="font-size:11px;color:#28a745;">{{ $active_customers }} Active</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#0f3460;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-cash-stack text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Monthly Revenue</div>
                    <div class="fw-bold fs-4">Rs. {{ number_format($monthly_revenue) }}</div>
                    <div style="font-size:11px;color:#6c757d;">Total: Rs. {{ number_format($total_revenue) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#f39c12;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-receipt text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Unpaid Invoices</div>
                    <div class="fw-bold fs-4">{{ $unpaid_invoices }}</div>
                    <div style="font-size:11px;color:#e74c3c;">{{ $overdue_invoices }} Overdue</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#e74c3c;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-chat-left-text text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Open Complaints</div>
                    <div class="fw-bold fs-4">{{ $open_complaints }}</div>
                    <div style="font-size:11px;color:#f39c12;">{{ $in_progress_complaints }} In Progress</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards Row 2 -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#27ae60;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-plug text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Active Connections</div>
                    <div class="fw-bold fs-4">{{ $active_connections }}</div>
                    <div style="font-size:11px;color:#6c757d;">Total: {{ $total_connections }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#8e44ad;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-pause-circle text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Suspended</div>
                    <div class="fw-bold fs-4">{{ $suspended_customers }}</div>
                    <div style="font-size:11px;color:#e74c3c;">{{ $terminated_customers }} Terminated</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#2980b9;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-receipt-cutoff text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Paid Invoices</div>
                    <div class="fw-bold fs-4">{{ $paid_invoices }}</div>
                    <div style="font-size:11px;color:#6c757d;">Total: {{ $total_invoices }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#e67e22;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-wallet2 text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Monthly Expenses</div>
                    <div class="fw-bold fs-4">Rs. {{ number_format($monthly_expenses) }}</div>
                    <div style="font-size:11px;color:#27ae60;">
                        Net: Rs. {{ number_format($monthly_revenue - $monthly_expenses) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Records -->
<div class="row g-3">
    <!-- Recent Customers -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people me-2"></i>Recent Customers</span>
                <a href="{{ route('customers.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Name</th>
                            <th>Phone</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_customers as $customer)
                        <tr>
                            <td class="ps-3">{{ $customer->name }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>
                                <span class="badge bg-{{ $customer->status == 'active' ? 'success' : ($customer->status == 'suspended' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($customer->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">No customers yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Complaints -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-chat-left-text me-2"></i>Recent Complaints</span>
                <a href="{{ route('complaints.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Customer</th>
                            <th>Title</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_complaints as $complaint)
                        <tr>
                            <td class="ps-3">{{ $complaint->customer->name ?? 'N/A' }}</td>
                            <td>{{ Str::limit($complaint->title, 25) }}</td>
                            <td>
                                <span class="badge bg-{{ $complaint->status == 'open' ? 'danger' : ($complaint->status == 'in_progress' ? 'warning' : 'success') }}">
                                    {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">No complaints yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Invoices -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-receipt me-2"></i>Recent Invoices</span>
                <a href="{{ route('invoices.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Invoice #</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_invoices as $invoice)
                        <tr>
                            <td class="ps-3">{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->customer->name ?? 'N/A' }}</td>
                            <td>Rs. {{ number_format($invoice->total_amount) }}</td>
                            <td>
                                <span class="badge bg-{{ $invoice->status == 'paid' ? 'success' : ($invoice->status == 'overdue' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No invoices yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-cash-stack me-2"></i>Recent Payments</span>
                <a href="{{ route('payments.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Customer</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_payments as $payment)
                        <tr>
                            <td class="ps-3">{{ $payment->customer->name ?? 'N/A' }}</td>
                            <td>Rs. {{ number_format($payment->amount_paid) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No payments yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection