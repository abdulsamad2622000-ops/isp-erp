 
@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-bell me-2"></i>Notifications</span>
        <a href="{{ route('notifications.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Create Notification
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-3">#</th>
                    <th>Customer</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Channel</th>
                    <th>Sent</th>
                    <th>Sent At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifications as $notification)
                <tr>
                    <td class="ps-3">{{ $loop->iteration }}</td>
                    <td>{{ $notification->customer->name ?? 'All Customers' }}</td>
                    <td>{{ Str::limit($notification->title, 30) }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $notification->type)) }}</td>
                    <td>{{ ucfirst($notification->channel) }}</td>
                    <td>
                        <span class="badge bg-{{ $notification->is_sent ? 'success' : 'warning' }}">
                            {{ $notification->is_sent ? 'Sent' : 'Pending' }}
                        </span>
                    </td>
                    <td>{{ $notification->sent_at ? \Carbon\Carbon::parse($notification->sent_at)->format('d M Y') : 'N/A' }}</td>
                    <td>
                        <a href="{{ route('notifications.edit', $notification) }}" class="btn btn-sm btn-warning text-white">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No notifications found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($notifications->hasPages())
    <div class="card-footer">
        {{ $notifications->links() }}
    </div>
    @endif
</div>
@endsection