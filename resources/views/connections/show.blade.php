@extends('layouts.app')

@section('title', 'Connection Detail')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-plug me-2"></i>Connection Detail</span>
        <a href="{{ route('connections.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td class="text-muted" style="width:40%">Customer</td>
                        <td><strong>{{ $connection->customer->name ?? 'N/A' }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Package</td>
                        <td>{{ $connection->package->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Area</td>
                        <td>{{ $connection->area->area_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Type</td>
                        <td>{{ ucfirst($connection->connection_type) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            <span class="badge bg-{{ $connection->status == 'active' ? 'success' : ($connection->status == 'suspended' ? 'warning' : 'danger') }}">
                                {{ ucfirst($connection->status) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td class="text-muted" style="width:40%">IP Address</td>
                        <td>{{ $connection->ip_address ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">MAC Address</td>
                        <td>{{ $connection->mac_address ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">PPPoE Username</td>
                        <td>{{ $connection->username ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">PPPoE Password</td>
                        <td>{{ $connection->password ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Installed</td>
                        <td>{{ \Carbon\Carbon::parse($connection->installation_date)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Technician</td>
                        <td>{{ $connection->technician->name ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="mt-3">
            <a href="{{ route('connections.edit', $connection) }}" class="btn btn-warning btn-sm text-white">
                <i class="bi bi-pencil"></i> Edit
            </a>
        </div>
    </div>
</div>
@endsection