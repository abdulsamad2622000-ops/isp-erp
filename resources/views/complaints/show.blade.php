@extends('layouts.app')

@section('title', 'Complaint Detail')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-chat-left-text me-2"></i>Complaint Detail</span>
        <div>
            <a href="{{ route('complaints.edit', $complaint) }}" class="btn btn-warning btn-sm text-white">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('complaints.index') }}" class="btn btn-secondary btn-sm ms-2">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td class="text-muted" style="width:40%">Customer</td>
                        <td><strong>{{ $complaint->customer->name ?? 'N/A' }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Phone</td>
                        <td>{{ $complaint->customer->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Title</td>
                        <td>{{ $complaint->title }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Category</td>
                        <td>{{ ucfirst($complaint->category) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Priority</td>
                        <td>
                            <span class="badge bg-{{ $complaint->priority == 'critical' ? 'danger' : ($complaint->priority == 'high' ? 'warning' : ($complaint->priority == 'medium' ? 'info' : 'secondary')) }}">
                                {{ ucfirst($complaint->priority) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td class="text-muted" style="width:40%">Status</td>
                        <td>
                            <span class="badge bg-{{ $complaint->status == 'open' ? 'danger' : ($complaint->status == 'in_progress' ? 'warning' : ($complaint->status == 'resolved' ? 'success' : 'secondary')) }}">
                                {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Assigned To</td>
                        <td>{{ $complaint->assignedTo->name ?? 'Unassigned' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Created</td>
                        <td>{{ $complaint->created_at->format('d M Y h:i A') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Resolved At</td>
                        <td>{{ $complaint->resolved_at ? $complaint->resolved_at->format('d M Y h:i A') : 'N/A' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-12">
                <label class="fw-semibold text-muted">Description</label>
                <div class="p-3 bg-light rounded mt-1">{{ $complaint->description }}</div>
            </div>
            @if($complaint->resolution_notes)
            <div class="col-12">
                <label class="fw-semibold text-muted">Resolution Notes</label>
                <div class="p-3 bg-light rounded mt-1">{{ $complaint->resolution_notes }}</div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection