 
@extends('layouts.app')

@section('title', 'Expenses')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-wallet2 me-2"></i>All Expenses</span>
        <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Add Expense
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-3">#</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Paid To</th>
                    <th>Date</th>
                    <th>Added By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                <tr>
                    <td class="ps-3">{{ $loop->iteration }}</td>
                    <td><strong>{{ $expense->title }}</strong></td>
                    <td>{{ ucfirst($expense->category) }}</td>
                    <td>Rs. {{ number_format($expense->amount) }}</td>
                    <td>{{ $expense->paid_to ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</td>
                    <td>{{ $expense->addedBy->name ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-sm btn-warning text-white">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No expenses found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($expenses->hasPages())
    <div class="card-footer">
        {{ $expenses->links() }}
    </div>
    @endif
</div>
@endsection