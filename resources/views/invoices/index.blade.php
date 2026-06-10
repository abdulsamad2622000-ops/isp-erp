@extends('layouts.app')

@section('title', 'Invoices')

@section('content')

{{-- Partial Payment Modal --}}
<div class="modal fade" id="partialModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-cash me-2"></i>Partial Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="partialForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Customer</label>
                        <input type="text" id="modalCustomerName" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Total Amount</label>
                        <input type="text" id="modalTotalAmount" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Already Paid</label>
                        <input type="text" id="modalAlreadyPaid" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Remaining</label>
                        <input type="text" id="modalRemaining" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Amount to Pay <span class="text-danger">*</span></label>
                        <input type="number" name="amount_paid" class="form-control" placeholder="Enter amount" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select" required>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="easypaisa">Easypaisa</option>
                            <option value="jazzcash">JazzCash</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-check-lg me-1"></i> Submit Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Hidden Mark Paid Forms --}}
@foreach($invoices as $invoice)
@if($invoice->status != 'paid')
<form id="markPaidForm{{ $invoice->id }}"
    action="{{ route('invoices.markPaid', $invoice) }}"
    method="POST" style="display:none;">
    @csrf
</form>
@endif
@endforeach

{{-- Hidden Mark Credit Forms --}}
@foreach($invoices as $invoice)
@if($invoice->status != 'paid' && $invoice->status != 'credit')
<form id="markCreditForm{{ $invoice->id }}"
    action="{{ route('invoices.markCredit', $invoice) }}"
    method="POST" style="display:none;">
    @csrf
</form>
@endif
@endforeach

{{-- Expiry Filter Bar --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="fw-semibold text-muted small me-1">
                <i class="bi bi-hourglass-split me-1"></i>Expiring Filter:
            </span>
            <a href="{{ route('invoices.index', ['expiry_days' => 1]) }}"
                class="btn btn-sm {{ request('expiry_days') == 1 ? 'btn-danger' : 'btn-outline-danger' }}">
                <i class="bi bi-hourglass me-1"></i> Expiring (1 Day)
            </a>
            <a href="{{ route('invoices.index', ['expiry_days' => 3]) }}"
                class="btn btn-sm {{ request('expiry_days') == 3 ? 'btn-warning' : 'btn-outline-warning' }}">
                <i class="bi bi-hourglass me-1"></i> Expiring (3 Days)
            </a>
            <a href="{{ route('invoices.index', ['expiry_days' => 7]) }}"
                class="btn btn-sm {{ request('expiry_days') == 7 ? 'btn-info' : 'btn-outline-info' }}">
                <i class="bi bi-hourglass me-1"></i> Expiring (1 Week)
            </a>
            <a href="{{ route('invoices.index', ['expiry_days' => 14]) }}"
                class="btn btn-sm {{ request('expiry_days') == 14 ? 'btn-secondary' : 'btn-outline-secondary' }}">
                <i class="bi bi-hourglass me-1"></i> Expiring (2 Weeks)
            </a>
            @if(request('expiry_days'))
            <a href="{{ route('invoices.index') }}" class="btn btn-sm btn-outline-dark ms-1">
                <i class="bi bi-x-lg"></i> Clear
            </a>
            @endif
        </div>
    </div>
</div>

{{-- Bulk Paid Form --}}
<form action="{{ route('invoices.bulkPaid') }}" method="POST" id="bulkForm">
@csrf

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="bi bi-receipt me-2"></i>All Invoices</span>
        <div class="d-flex gap-2 flex-wrap">
            <button type="submit" form="bulkForm" class="btn btn-success btn-sm" id="bulkPaidBtn" style="display:none!important;">
                <i class="bi bi-check-all me-1"></i> Bulk Paid
            </button>
            <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Add Invoice
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible m-3 mb-0" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                        </th>
                        <th>#</th>
                        <th>Invoice No</th>
                        <th>Customer</th>
                        <th>Package</th>
                        <th>Amount</th>
                        <th>Paid</th>
                        <th>Remaining</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                    @php
                        $totalPaid = $invoice->payments->sum('amount_paid');
                        $remaining = $invoice->total_amount - $totalPaid;
                    @endphp
                    <tr class="{{ $invoice->status == 'credit' ? 'table-info' : '' }}">
                        <td class="ps-3">
                            @if($invoice->status != 'paid')
                            <input type="checkbox" name="invoice_ids[]" value="{{ $invoice->id }}"
                                class="form-check-input invoice-check" form="bulkForm">
                            @endif
                        </td>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="fw-semibold">{{ $invoice->invoice_number }}</span></td>
                        <td>{{ $invoice->customer->name ?? 'N/A' }}</td>
                        <td>{{ $invoice->package->name ?? 'N/A' }}</td>
                        <td>Rs. {{ number_format($invoice->total_amount, 0) }}</td>
                        <td>Rs. {{ number_format($totalPaid, 0) }}</td>
                        <td>
                            @if($remaining > 0)
                                <span class="text-danger fw-semibold">Rs. {{ number_format($remaining, 0) }}</span>
                            @else
                                <span class="text-success">—</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($invoice->issue_date)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</td>
                        <td>
                            @if($invoice->status == 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($invoice->status == 'partial')
                                <span class="badge bg-warning text-dark">Partial</span>
                            @elseif($invoice->status == 'unpaid')
                                <span class="badge bg-danger">Unpaid</span>
                            @elseif($invoice->status == 'credit')
                                <span class="badge" style="background:#6f42c1;">Credit</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($invoice->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                @if($invoice->status != 'paid')
                                {{-- Full Paid --}}
                                <button type="button" class="btn btn-sm btn-success" title="Mark Full Paid"
                                    onclick="if(confirm('Mark as fully paid?')) { document.getElementById('markPaidForm{{ $invoice->id }}').submit(); }">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                                {{-- Partial Payment --}}
                                <button type="button" class="btn btn-sm btn-warning text-white"
                                    title="Partial Payment"
                                    onclick="openPartialModal(
                                        {{ $invoice->id }},
                                        '{{ addslashes($invoice->customer->name ?? '') }}',
                                        {{ $invoice->total_amount }},
                                        {{ $totalPaid }},
                                        {{ $remaining }}
                                    )">
                                    <i class="bi bi-cash"></i>
                                </button>
                                @endif
                                {{-- Credit Button --}}
                                @if($invoice->status != 'paid' && $invoice->status != 'credit')
                                <button type="button" class="btn btn-sm text-white" title="Activate on Credit"
                                    style="background:#6f42c1;"
                                    onclick="if(confirm('Activate customer on credit? Payment will be pending.')) { document.getElementById('markCreditForm{{ $invoice->id }}').submit(); }">
                                    <i class="bi bi-person-check"></i>
                                </button>
                                @endif
                                <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-info text-white">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-sm btn-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('invoices.destroy', $invoice) }}" method="POST"
                                    style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center text-muted py-4">No invoices found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($invoices->hasPages())
    <div class="card-footer">
        {{ $invoices->links() }}
    </div>
    @endif
</div>

</form>

<script>
const selectAll = document.getElementById('selectAll');
const checkboxes = document.querySelectorAll('.invoice-check');
const bulkBtn = document.getElementById('bulkPaidBtn');

function toggleBulkBtn() {
    const checked = document.querySelectorAll('.invoice-check:checked').length;
    bulkBtn.style.setProperty('display', checked > 0 ? 'inline-block' : 'none', 'important');
}

selectAll.addEventListener('change', function () {
    checkboxes.forEach(cb => cb.checked = this.checked);
    toggleBulkBtn();
});

checkboxes.forEach(cb => {
    cb.addEventListener('change', toggleBulkBtn);
});

function openPartialModal(invoiceId, customerName, totalAmount, alreadyPaid, remaining) {
    document.getElementById('modalCustomerName').value = customerName;
    document.getElementById('modalTotalAmount').value = 'Rs. ' + totalAmount.toLocaleString();
    document.getElementById('modalAlreadyPaid').value = 'Rs. ' + alreadyPaid.toLocaleString();
    document.getElementById('modalRemaining').value = 'Rs. ' + remaining.toLocaleString();
    document.getElementById('partialForm').action = '/invoices/' + invoiceId + '/partial-payment';
    new bootstrap.Modal(document.getElementById('partialModal')).show();
}
</script>

@endsection