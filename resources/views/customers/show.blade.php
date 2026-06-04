 
@extends('layouts.app')

@section('title', 'Customer Detail')

@section('content')
<div class="row g-3">
    <!-- Customer Info -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person me-2"></i>Customer Info
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tr>
                        <td class="text-muted" style="width:40%">Name</td>
                        <td><strong>{{ $customer->name }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">CNIC</td>
                        <td>{{ $customer->cnic ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Phone</td>
                        <td>{{ $customer->phone }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">WhatsApp</td>
                        <td>{{ $customer->whatsapp ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Email</td>
                        <td>{{ $customer->email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Area</td>
                        <td>{{ $customer->area->area_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Address</td>
                        <td>{{ $customer->address }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Joined</td>
                        <td>{{ \Carbon\Carbon::parse($customer->joining_date)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            <span class="badge bg-{{ $customer->status == 'active' ? 'success' : ($customer->status == 'suspended' ? 'warning' : 'danger') }}">
                                {{ ucfirst($customer->status) }}
                            </span>
                        </td>
                    </tr>
                </table>
                <div class="mt-3 d-flex gap-2">
                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning btn-sm text-white">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Connections -->
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-plug me-2"></i>Connections</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Package</th>
                            <th>IP Address</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Installed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->connections as $connection)
                        <tr>
                            <td class="ps-3">{{ $connection->package->name ?? 'N/A' }}</td>
                            <td>{{ $connection->ip_address ?? 'N/A' }}</td>
                            <td>{{ ucfirst($connection->connection_type) }}</td>
                            <td>
                                <span class="badge bg-{{ $connection->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($connection->status) }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($connection->installation_date)->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">No connections</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Invoices -->
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-receipt me-2"></i>Recent Invoices</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Invoice #</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->invoices->take(5) as $invoice)
                        <tr>
                            <td class="ps-3">{{ $invoice->invoice_number }}</td>
                            <td>Rs. {{ number_format($invoice->total_amount) }}</td>
                            <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $invoice->status == 'paid' ? 'success' : ($invoice->status == 'overdue' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No invoices</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Complaints -->
        <div class="card">
            <div class="card-header"><i class="bi bi-chat-left-text me-2"></i>Recent Complaints</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Title</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->complaints->take(5) as $complaint)
                        <tr>
                            <td class="ps-3">{{ Str::limit($complaint->title, 30) }}</td>
                            <td>{{ ucfirst($complaint->category) }}</td>
                            <td>
                                <span class="badge bg-{{ $complaint->priority == 'critical' ? 'danger' : ($complaint->priority == 'high' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($complaint->priority) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $complaint->status == 'open' ? 'danger' : ($complaint->status == 'in_progress' ? 'warning' : 'success') }}">
                                    {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No complaints</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection