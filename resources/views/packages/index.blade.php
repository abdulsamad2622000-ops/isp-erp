@extends('layouts.app')

@section('title', 'Packages')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-box me-2"></i>All Packages</span>
        <a href="{{ route('packages.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Add Package
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-3">#</th>
                    <th>Name</th>
                    <th>Speed</th>
                    <th>Price</th>
                    <th>Validity</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($packages as $package)
                <tr>
                    <td class="ps-3">{{ $loop->iteration }}</td>
                    <td><strong>{{ $package->name }}</strong></td>
                    <td>{{ $package->speed }}</td>
                    <td>Rs. {{ number_format($package->price) }}</td>
                    <td>{{ ucfirst($package->validity) }}</td>
                    <td>{{ ucfirst($package->type) }}</td>
                    <td>
                        <span class="badge bg-{{ $package->is_active ? 'success' : 'danger' }}">
                            {{ $package->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('packages.edit', $package) }}" class="btn btn-sm btn-warning text-white">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('packages.destroy', $package) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No packages found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($packages->hasPages())
    <div class="card-footer">
        {{ $packages->links() }}
    </div>
    @endif
</div>
@endsection