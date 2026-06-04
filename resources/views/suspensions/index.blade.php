 
@extends('layouts.app')

@section('title', 'Suspensions')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-pause-circle me-2"></i>Suspensions</span>
        <a href="{{ route('suspensions.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Add Suspension
        </a>
    </div>
    <div class="card-body p-0"><div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-3">#</th>
                    <th>Customer</th>
                    <th>Reason</th>
                    <th>Suspension Date</th>
                    <th>Reconnection Date</th>
                    <th>Actioned By</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suspensions as $suspension)
                <tr>
                    <td class="ps-3">{{ $loop->iteration }}</td>
                    <td>{{ $suspension->customer->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $suspension->reason)) }}</td>
                    <td>{{ \Carbon\Carbon::parse($suspension->suspension_date)->format('d M Y') }}</td>
                    <td>{{ $suspension->reconnection_date ? \Carbon\Carbon::parse($suspension->reconnection_date)->format('d M Y') : 'N/A' }}</td>
                    <td>{{ $suspension->actionedBy->name ?? 'N/A' }}</td>
                    <td>
                        <span class="badge bg-{{ $suspension->status == 'suspended' ? 'danger' : 'success' }}">
                            {{ ucfirst($suspension->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('suspensions.edit', $suspension) }}" class="btn btn-sm btn-warning text-white">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('suspensions.destroy', $suspension) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No suspensions found</td>
                </tr>
                @endforelse
            </tbody>
        </table></div></div>
    @if($suspensions->hasPages())
    <div class="card-footer">
        {{ $suspensions->links() }}
    </div>
    @endif
</div>
@endsection