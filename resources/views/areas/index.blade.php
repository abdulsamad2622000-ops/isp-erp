@extends('layouts.app')

@section('title', 'Areas')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-geo-alt me-2"></i>All Areas</span>
        <a href="{{ route('areas.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Add Area
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-3">#</th>
                    <th>City</th>
                    <th>Area Name</th>
                    <th>Sub Area</th>
                    <th>Coverage Details</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($areas as $area)
                <tr>
                    <td class="ps-3">{{ $loop->iteration }}</td>
                    <td>{{ $area->city }}</td>
                    <td>{{ $area->area_name }}</td>
                    <td>{{ $area->sub_area ?? 'N/A' }}</td>
                    <td>{{ $area->coverage_details ?? 'N/A' }}</td>
                    <td>
                        <span class="badge bg-{{ $area->is_active ? 'success' : 'danger' }}">
                            {{ $area->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('areas.edit', $area) }}" class="btn btn-sm btn-warning text-white">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('areas.destroy', $area) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No areas found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($areas->hasPages())
    <div class="card-footer">
        {{ $areas->links() }}
    </div>
    @endif
</div>
@endsection