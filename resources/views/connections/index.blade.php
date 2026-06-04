@extends('layouts.app')

@section('title', 'Connections')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-plug me-2"></i>All Connections</span>
        <a href="{{ route('connections.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Add Connection
        </a>
    </div>
    <div class="card-body p-0"><div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-3">#</th>
                    <th>Customer</th>
                    <th>Package</th>
                    <th>Area</th>
                    <th>IP Address</th>
                    <th>Type</th>
                    <th>Installed</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($connections as $connection)
                <tr>
                    <td class="ps-3">{{ $loop->iteration }}</td>
                    <td>{{ $connection->customer->name ?? 'N/A' }}</td>
                    <td>{{ $connection->package->name ?? 'N/A' }}</td>
                    <td>{{ $connection->area->area_name ?? 'N/A' }}</td>
                    <td>{{ $connection->ip_address ?? 'N/A' }}</td>
                    <td>{{ ucfirst($connection->connection_type) }}</td>
                    <td>{{ \Carbon\Carbon::parse($connection->installation_date)->format('d M Y') }}</td>
                    <td>
                        <span class="badge bg-{{ $connection->status == 'active' ? 'success' : ($connection->status == 'suspended' ? 'warning' : 'danger') }}">
                            {{ ucfirst($connection->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('connections.show', $connection) }}" class="btn btn-sm btn-info text-white">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('connections.edit', $connection) }}" class="btn btn-sm btn-warning text-white">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('connections.destroy', $connection) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">No connections found</td>
                </tr>
                @endforelse
            </tbody>
        </table></div></div>
    @if($connections->hasPages())
    <div class="card-footer">
        {{ $connections->links() }}
    </div>
    @endif
</div>
@endsection