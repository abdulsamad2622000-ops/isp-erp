@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<style>
.kpi-card {
    cursor: pointer;
    transition: transform 0.15s, box-shadow 0.15s;
    border: 2px solid transparent !important;
}
.kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}
.kpi-card.active {
    border: 2px solid #0d6efd !important;
}
.detail-panel {
    display: none;
    animation: fadeIn 0.2s ease;
}
.detail-panel.show {
    display: block;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>

<!-- KPI Row 1 -->
<div class="row g-3 mb-2">

    {{-- Total Customers --}}
    <div class="col-xl-3 col-md-6">
        <div class="card kpi-card" onclick="togglePanel('customers', this)">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#e94560;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-people text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Total Customers</div>
                    <div class="fw-bold fs-4">{{ $total_customers }}</div>
                    <div style="font-size:11px;color:#28a745;">{{ $active_customers }} Active</div>
                </div>
                <div class="ms-auto"><i class="bi bi-chevron-down text-muted"></i></div>
            </div>
        </div>
    </div>

    {{-- Unpaid Invoices --}}
    <div class="col-xl-3 col-md-6">
        <div class="card kpi-card" onclick="togglePanel('unpaid', this)">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#f39c12;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-receipt text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Unpaid Invoices</div>
                    <div class="fw-bold fs-4">{{ $unpaid_invoices }}</div>
                    <div style="font-size:11px;color:#e74c3c;">{{ $overdue_invoices }} Overdue</div>
                </div>
                <div class="ms-auto"><i class="bi bi-chevron-down text-muted"></i></div>
            </div>
        </div>
    </div>

    {{-- Credit Invoices --}}
    <div class="col-xl-3 col-md-6">
        <div class="card kpi-card" onclick="togglePanel('credit', this)">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#6f42c1;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-person-check text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Credit Invoices</div>
                    <div class="fw-bold fs-4">{{ $credit_invoices }}</div>
                    <div style="font-size:11px;color:#e74c3c;">Payment Pending</div>
                </div>
                <div class="ms-auto"><i class="bi bi-chevron-down text-muted"></i></div>
            </div>
        </div>
    </div>

    {{-- Expiring Customers --}}
    <div class="col-xl-3 col-md-6">
        <div class="card kpi-card" onclick="togglePanel('expiring', this)">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#e74c3c;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-hourglass-split text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Expiring (7 Days)</div>
                    <div class="fw-bold fs-4">{{ $expiring_customers }}</div>
                    <div style="font-size:11px;color:#f39c12;">Renewal needed</div>
                </div>
                <div class="ms-auto"><i class="bi bi-chevron-down text-muted"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- KPI Row 2 -->
<div class="row g-3 mb-2">

    {{-- Suspended --}}
    <div class="col-xl-3 col-md-6">
        <div class="card kpi-card" onclick="togglePanel('suspended', this)">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#8e44ad;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-pause-circle text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Suspended</div>
                    <div class="fw-bold fs-4">{{ $suspended_customers }}</div>
                    <div style="font-size:11px;color:#e74c3c;">{{ $terminated_customers }} Terminated</div>
                </div>
                <div class="ms-auto"><i class="bi bi-chevron-down text-muted"></i></div>
            </div>
        </div>
    </div>

    {{-- Monthly Expenses --}}
    <div class="col-xl-3 col-md-6">
        <div class="card kpi-card" onclick="togglePanel('expenses', this)">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#e67e22;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-wallet2 text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Monthly Expenses</div>
                    <div class="fw-bold fs-4">Rs. {{ number_format($monthly_expenses) }}</div>
                    <div style="font-size:11px;color:#27ae60;">Net: Rs. {{ number_format($monthly_revenue - $monthly_expenses) }}</div>
                </div>
                <div class="ms-auto"><i class="bi bi-chevron-down text-muted"></i></div>
            </div>
        </div>
    </div>

    {{-- Open Complaints --}}
    <div class="col-xl-3 col-md-6">
        <div class="card kpi-card" onclick="togglePanel('complaints', this)">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#e74c3c;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-chat-left-text text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Open Complaints</div>
                    <div class="fw-bold fs-4">{{ $open_complaints }}</div>
                    <div style="font-size:11px;color:#f39c12;">{{ $in_progress_complaints }} In Progress</div>
                </div>
                <div class="ms-auto"><i class="bi bi-chevron-down text-muted"></i></div>
            </div>
        </div>
    </div>

    {{-- Monthly Revenue --}}
    <div class="col-xl-3 col-md-6">
        <div class="card kpi-card" onclick="togglePanel('payments', this)">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#0f3460;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-cash-stack text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Monthly Revenue</div>
                    <div class="fw-bold fs-4">Rs. {{ number_format($monthly_revenue) }}</div>
                    <div style="font-size:11px;color:#6c757d;">Total: Rs. {{ number_format($total_revenue) }}</div>
                </div>
                <div class="ms-auto"><i class="bi bi-chevron-down text-muted"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- KPI Row 3 -->
<div class="row g-3 mb-2">
    {{-- Notifications --}}
    <div class="col-xl-3 col-md-6">
        <div class="card kpi-card" onclick="togglePanel('notifications', this)">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#17a2b8;width:55px;height:55px;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                    <i class="bi bi-bell text-white fs-4"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Notifications</div>
                    <div class="fw-bold fs-4">{{ $pending_notifications }}</div>
                    <div style="font-size:11px;color:#6c757d;">{{ $total_notifications }} Total</div>
                </div>
                <div class="ms-auto"><i class="bi bi-chevron-down text-muted"></i></div>
            </div>
        </div>
    </div>
</div>

{{-- Expiry Filters --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="fw-semibold text-muted small me-1">
                <i class="bi bi-hourglass-split me-1"></i>Expiry Filter:
            </span>
            <a href="{{ route('customers.index', ['expiry_days' => 1]) }}" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-hourglass me-1"></i> Expiring (1 Day)
            </a>
            <a href="{{ route('customers.index', ['expiry_days' => 3]) }}" class="btn btn-sm btn-outline-warning">
                <i class="bi bi-hourglass me-1"></i> Expiring (3 Days)
            </a>
            <a href="{{ route('customers.index', ['expiry_days' => 7]) }}" class="btn btn-sm btn-outline-info">
                <i class="bi bi-hourglass me-1"></i> Expiring (1 Week)
            </a>
            <a href="{{ route('customers.index', ['expiry_days' => 14]) }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-hourglass me-1"></i> Expiring (2 Weeks)
            </a>
        </div>
    </div>
</div>

{{-- ============ DETAIL PANELS ============ --}}

{{-- Customers Panel --}}
<div id="panel-customers" class="detail-panel mb-3">
    <div class="card border-danger">
        <div class="card-header d-flex justify-content-between align-items-center" style="background:#fdf0f0;">
            <span><i class="bi bi-people me-2 text-danger"></i><strong>All Customers</strong></span>
            <a href="{{ route('customers.index') }}" class="btn btn-sm btn-danger">View All</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead><tr><th class="ps-3">Name</th><th>Phone</th><th>Area</th><th>Expiry</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($recent_customers as $c)
                    <tr>
                        <td class="ps-3">{{ $c->name }}</td>
                        <td>{{ $c->phone }}</td>
                        <td>{{ $c->area->area_name ?? 'N/A' }}</td>
                        <td>{{ $c->expiry_date ? \Carbon\Carbon::parse($c->expiry_date)->format('d M Y') : '—' }}</td>
                        <td><span class="badge bg-{{ $c->status == 'active' ? 'success' : ($c->status == 'suspended' ? 'warning text-dark' : 'danger') }}">{{ ucfirst($c->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">No customers yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Unpaid Invoices Panel --}}
<div id="panel-unpaid" class="detail-panel mb-3">
    <div class="card border-warning">
        <div class="card-header d-flex justify-content-between align-items-center" style="background:#fffbf0;">
            <span><i class="bi bi-receipt me-2 text-warning"></i><strong>Unpaid Invoices</strong></span>
            <a href="{{ route('invoices.index') }}" class="btn btn-sm btn-warning text-dark">View All</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead><tr><th class="ps-3">Invoice #</th><th>Customer</th><th>Amount</th><th>Due Date</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($recent_invoices->whereIn('status', ['unpaid','overdue']) as $inv)
                    <tr>
                        <td class="ps-3">{{ $inv->invoice_number }}</td>
                        <td>{{ $inv->customer->name ?? 'N/A' }}</td>
                        <td>Rs. {{ number_format($inv->total_amount) }}</td>
                        <td>{{ \Carbon\Carbon::parse($inv->due_date)->format('d M Y') }}</td>
                        <td><span class="badge bg-{{ $inv->status == 'overdue' ? 'danger' : 'warning text-dark' }}">{{ ucfirst($inv->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">No unpaid invoices</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Credit Invoices Panel --}}
<div id="panel-credit" class="detail-panel mb-3">
    <div class="card" style="border:1.5px solid #6f42c1;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background:#f5f0ff;">
            <span><i class="bi bi-person-check me-2" style="color:#6f42c1;"></i><strong>Credit Invoices</strong></span>
            <a href="{{ route('invoices.index') }}" class="btn btn-sm text-white" style="background:#6f42c1;">View All</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead><tr><th class="ps-3">Invoice #</th><th>Customer</th><th>Amount</th><th>Due Date</th></tr></thead>
                <tbody>
                    @forelse($recent_invoices->where('status','credit') as $inv)
                    <tr>
                        <td class="ps-3">{{ $inv->invoice_number }}</td>
                        <td>{{ $inv->customer->name ?? 'N/A' }}</td>
                        <td>Rs. {{ number_format($inv->total_amount) }}</td>
                        <td>{{ \Carbon\Carbon::parse($inv->due_date)->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">No credit invoices</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Expiring Customers Panel --}}
<div id="panel-expiring" class="detail-panel mb-3">
    <div class="card border-danger">
        <div class="card-header d-flex justify-content-between align-items-center" style="background:#fff5f5;">
            <span><i class="bi bi-hourglass-split me-2 text-danger"></i><strong>Expiring in 7 Days</strong></span>
            <a href="{{ route('customers.index', ['expiry_days' => 7]) }}" class="btn btn-sm btn-danger">View All</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead><tr><th class="ps-3">Name</th><th>Phone</th><th>Expiry Date</th><th>Days Left</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($expiring_customers_list as $c)
                    @php $daysLeft = (int) now()->diffInDays(\Carbon\Carbon::parse($c->expiry_date), false); @endphp
                    <tr>
                        <td class="ps-3">{{ $c->name }}</td>
                        <td>{{ $c->phone }}</td>
                        <td>{{ \Carbon\Carbon::parse($c->expiry_date)->format('d M Y') }}</td>
                        <td><span class="badge bg-{{ $daysLeft <= 3 ? 'danger' : 'warning text-dark' }}">{{ $daysLeft }}d left</span></td>
                        <td><span class="badge bg-{{ $c->status == 'active' ? 'success' : 'warning text-dark' }}">{{ ucfirst($c->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">No expiring customers</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Suspended Panel --}}
<div id="panel-suspended" class="detail-panel mb-3">
    <div class="card" style="border:1.5px solid #8e44ad;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background:#fdf5ff;">
            <span><i class="bi bi-pause-circle me-2" style="color:#8e44ad;"></i><strong>Suspended / Terminated Customers</strong></span>
            <a href="{{ route('customers.index') }}" class="btn btn-sm text-white" style="background:#8e44ad;">View All</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead><tr><th class="ps-3">Name</th><th>Phone</th><th>Area</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($suspended_customers_list as $c)
                    <tr>
                        <td class="ps-3">{{ $c->name }}</td>
                        <td>{{ $c->phone }}</td>
                        <td>{{ $c->area->area_name ?? 'N/A' }}</td>
                        <td><span class="badge bg-{{ $c->status == 'suspended' ? 'warning text-dark' : 'danger' }}">{{ ucfirst($c->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">No suspended customers</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Expenses Panel --}}
<div id="panel-expenses" class="detail-panel mb-3">
    <div class="card border-warning">
        <div class="card-header d-flex justify-content-between align-items-center" style="background:#fffbf0;">
            <span><i class="bi bi-wallet2 me-2 text-warning"></i><strong>Monthly Expenses</strong></span>
            <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-warning text-dark">View All</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead><tr><th class="ps-3">Title</th><th>Amount</th><th>Date</th></tr></thead>
                <tbody>
                    @forelse($recent_expenses as $exp)
                    <tr>
                        <td class="ps-3">{{ $exp->title ?? $exp->description ?? 'N/A' }}</td>
                        <td>Rs. {{ number_format($exp->amount) }}</td>
                        <td>{{ \Carbon\Carbon::parse($exp->expense_date)->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-muted py-3">No expenses this month</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Complaints Panel --}}
<div id="panel-complaints" class="detail-panel mb-3">
    <div class="card border-danger">
        <div class="card-header d-flex justify-content-between align-items-center" style="background:#fff5f5;">
            <span><i class="bi bi-chat-left-text me-2 text-danger"></i><strong>Open Complaints</strong></span>
            <a href="{{ route('complaints.index') }}" class="btn btn-sm btn-danger">View All</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead><tr><th class="ps-3">Customer</th><th>Title</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($recent_complaints as $complaint)
                    <tr>
                        <td class="ps-3">{{ $complaint->customer->name ?? 'N/A' }}</td>
                        <td>{{ Str::limit($complaint->title, 30) }}</td>
                        <td><span class="badge bg-{{ $complaint->status == 'open' ? 'danger' : ($complaint->status == 'in_progress' ? 'warning text-dark' : 'success') }}">{{ ucfirst(str_replace('_', ' ', $complaint->status)) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-muted py-3">No complaints yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Payments Panel --}}
<div id="panel-payments" class="detail-panel mb-3">
    <div class="card border-primary">
        <div class="card-header d-flex justify-content-between align-items-center" style="background:#f0f4ff;">
            <span><i class="bi bi-cash-stack me-2 text-primary"></i><strong>Recent Payments</strong></span>
            <a href="{{ route('payments.index') }}" class="btn btn-sm btn-primary">View All</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead><tr><th class="ps-3">Customer</th><th>Amount</th><th>Method</th><th>Date</th></tr></thead>
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

{{-- Notifications Panel --}}
<div id="panel-notifications" class="detail-panel mb-3">
    <div class="card" style="border:1.5px solid #17a2b8;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background:#f0feff;">
            <span><i class="bi bi-bell me-2" style="color:#17a2b8;"></i><strong>Recent Notifications</strong></span>
            <a href="{{ route('notifications.index') }}" class="btn btn-sm text-white" style="background:#17a2b8;">View All</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Customer</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Channel</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_notifications as $notif)
                    <tr>
                        <td class="ps-3">{{ $notif->customer->name ?? 'All Customers' }}</td>
                        <td>{{ Str::limit($notif->title, 25) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $notif->type)) }}</td>
                        <td>{{ ucfirst($notif->channel) }}</td>
                        <td>
                            <span class="badge bg-{{ $notif->is_sent ? 'success' : 'warning text-dark' }}">
                                {{ $notif->is_sent ? 'Sent' : 'Pending' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">No notifications yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Recent Payments Always Visible --}}
<div class="row g-3 mt-1">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-cash-stack me-2"></i>Recent Payments</span>
                <a href="{{ route('payments.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th class="ps-3">Customer</th><th>Amount</th><th>Method</th><th>Date</th></tr>
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

<script>
function togglePanel(name, cardEl) {
    const panel = document.getElementById('panel-' + name);
    const allPanels = document.querySelectorAll('.detail-panel');
    const allCards = document.querySelectorAll('.kpi-card');
    const isOpen = panel.classList.contains('show');

    allPanels.forEach(p => p.classList.remove('show'));
    allCards.forEach(c => c.classList.remove('active'));

    if (!isOpen) {
        panel.classList.add('show');
        cardEl.classList.add('active');
        setTimeout(() => {
            panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 50);
    }
}
</script>

@endsection