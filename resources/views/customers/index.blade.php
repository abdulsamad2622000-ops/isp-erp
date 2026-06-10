@extends('layouts.app')

@section('title', 'Customers')

@section('content')

{{-- Import Modal --}}
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-upload me-2"></i>Import Customers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('customers.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Excel / CSV File</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">Accepted: .xlsx, .xls, .csv — Max 2MB</div>
                    </div>
                    <div class="alert alert-info p-2 small mb-0">
                        <strong>Required columns:</strong> name, phone<br>
                        <strong>Optional:</strong> user_id, cnic, whatsapp, email, address, area, status, due_date, expiry_date<br>
                        <strong>Status values:</strong> active / suspended / terminated
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-upload me-1"></i> Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Expiry Filter Bar --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="fw-semibold text-muted small me-1"><i class="bi bi-hourglass-split me-1"></i>Expiry Filter:</span>
            <a href="{{ route('customers.index', ['expiry_days' => 1]) }}"
                class="btn btn-sm {{ $expiryDays == 1 ? 'btn-danger' : 'btn-outline-danger' }}">
                <i class="bi bi-hourglass-split me-1"></i>Expiring (1 Day)
            </a>
            <a href="{{ route('customers.index', ['expiry_days' => 3]) }}"
                class="btn btn-sm {{ $expiryDays == 3 ? 'btn-warning' : 'btn-outline-warning' }}">
                <i class="bi bi-hourglass-split me-1"></i>Expiring (3 Days)
            </a>
            <a href="{{ route('customers.index', ['expiry_days' => 7]) }}"
                class="btn btn-sm {{ $expiryDays == 7 ? 'btn-info' : 'btn-outline-info' }}">
                <i class="bi bi-hourglass-split me-1"></i>Expiring (1 Week)
            </a>
            <a href="{{ route('customers.index', ['expiry_days' => 14]) }}"
                class="btn btn-sm {{ $expiryDays == 14 ? 'btn-secondary' : 'btn-outline-secondary' }}">
                <i class="bi bi-hourglass-split me-1"></i>Expiring (2 Weeks)
            </a>
            @if($expiryDays)
            <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline-dark ms-1">
                <i class="bi bi-x-lg"></i> Clear
            </a>
            <span class="badge bg-dark ms-1">Expiring within {{ $expiryDays }} day(s)</span>
            @endif
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="bi bi-people me-2"></i>All Customers</span>
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-upload me-1"></i> Import Excel
            </button>
            <a href="{{ route('customers.export') }}" class="btn btn-warning btn-sm text-dark">
                <i class="bi bi-download me-1"></i> Export Excel
            </a>
            <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Add Customer
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
                        <th class="ps-3">#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Area</th>
                        <th>Due Date</th>
                        <th>Expiry Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    @php
                        $daysLeft = $customer->expiry_date
                            ? (int) now()->diffInDays(\Carbon\Carbon::parse($customer->expiry_date), false)
                            : null;
                    @endphp
                    <tr class="{{ $daysLeft !== null && $daysLeft <= 3 && $daysLeft >= 0 ? 'table-danger' : ($daysLeft !== null && $daysLeft <= 5 && $daysLeft >= 0 ? 'table-warning' : '') }}">
                        <td class="ps-3">{{ $loop->iteration }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->area->area_name ?? 'N/A' }}</td>
                        <td>{{ $customer->due_date ? \Carbon\Carbon::parse($customer->due_date)->format('d M Y') : '—' }}</td>
                        <td>
                            @if($customer->expiry_date)
                                {{ \Carbon\Carbon::parse($customer->expiry_date)->format('d M Y') }}
                                @if($daysLeft !== null)
                                    <span class="badge bg-{{ $daysLeft < 0 ? 'danger' : ($daysLeft <= 3 ? 'danger' : ($daysLeft <= 5 ? 'warning text-dark' : 'secondary')) }} ms-1">
                                        {{ $daysLeft < 0 ? 'Expired' : $daysLeft.'d left' }}
                                    </span>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $customer->status == 'active' ? 'success' : ($customer->status == 'suspended' ? 'warning text-dark' : 'danger') }}">
                                {{ ucfirst($customer->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-info text-white">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-warning text-white">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No customers found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($customers->hasPages())
    <div class="card-footer">
        {{ $customers->links() }}
    </div>
    @endif
</div>

@endsection