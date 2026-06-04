@extends('layouts.app')

@section('title', 'Complaints')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-chat-left-text me-2"></i>All Complaints</span>
        <a href="{{ route('complaints.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Add Complaint
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-3">#</th>
                    <th>Customer</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Priority</th>
                    <th>Assigned To</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($complaints as $complaint)
                <tr>
                    <td class="ps-3">{{ $loop->iteration }}</td>
                    <td>{{ $complaint->customer->name ?? 'N/A' }}</td>
                    <td>{{ Str::limit($complaint->title, 30) }}</td>
                    <td>{{ ucfirst($complaint->category) }}</td>
                    <td>
                        <span class="badge bg-{{ $complaint->priority == 'critical' ? 'danger' : ($complaint->priority == 'high' ? 'warning' : ($complaint->priority == 'medium' ? 'info' : 'secondary')) }}">
                            {{ ucfirst($complaint->priority) }}
                        </span>
                    </td>
                    <td>{{ $complaint->assignedTo->name ?? 'Unassigned' }}</td>
                    <td>
                        <span class="badge bg-{{ $complaint->status == 'open' ? 'danger' : ($complaint->status == 'in_progress' ? 'warning' : ($complaint->status == 'resolved' ? 'success' : 'secondary')) }}">
                            {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('complaints.show', $complaint) }}" class="btn btn-sm btn-info text-white">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('complaints.edit', $complaint) }}" class="btn btn-sm btn-warning text-white">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('complaints.destroy', $complaint) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No complaints found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($complaints->hasPages())
    <div class="card-footer">
        {{ $complaints->links() }}
    </div>
    @endif
</div>
@endsection