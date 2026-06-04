@extends('layouts.app')

@section('title', 'Inventory')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-archive me-2"></i>Inventory</span>
        <a href="{{ route('inventory.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Add Item
        </a>
    </div>
    <div class="card-body p-0"><div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-3">#</th>
                    <th>Item Name</th>
                    <th>Model</th>
                    <th>Category</th>
                    <th>Total Stock</th>
                    <th>Available</th>
                    <th>Assigned</th>
                    <th>Unit Price</th>
                    <th>Supplier</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventory as $item)
                <tr>
                    <td class="ps-3">{{ $loop->iteration }}</td>
                    <td><strong>{{ $item->item_name }}</strong></td>
                    <td>{{ $item->model ?? 'N/A' }}</td>
                    <td>{{ ucfirst($item->category) }}</td>
                    <td>{{ $item->total_stock }}</td>
                    <td>
                        <span class="badge bg-{{ $item->available_stock > 0 ? 'success' : 'danger' }}">
                            {{ $item->available_stock }}
                        </span>
                    </td>
                    <td>{{ $item->assigned_stock }}</td>
                    <td>{{ $item->unit_price ? 'Rs. ' . number_format($item->unit_price) : 'N/A' }}</td>
                    <td>{{ $item->supplier ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('inventory.edit', $item) }}" class="btn btn-sm btn-warning text-white">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('inventory.destroy', $item) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">No inventory items found</td>
                </tr>
                @endforelse
            </tbody>
        </table></div></div>
    @if($inventory->hasPages())
    <div class="card-footer">
        {{ $inventory->links() }}
    </div>
    @endif
</div>
@endsection