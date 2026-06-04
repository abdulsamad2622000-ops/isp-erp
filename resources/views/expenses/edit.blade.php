 
@extends('layouts.app')

@section('title', 'Edit Expense')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-pencil me-2"></i>Edit Expense — {{ $expense->title }}
    </div>
    <div class="card-body">
        <form action="{{ route('expenses.update', $expense) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title', $expense->title) }}">
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror">
                        <option value="salary" {{ old('category', $expense->category) == 'salary' ? 'selected' : '' }}>Salary</option>
                        <option value="rent" {{ old('category', $expense->category) == 'rent' ? 'selected' : '' }}>Rent</option>
                        <option value="electricity" {{ old('category', $expense->category) == 'electricity' ? 'selected' : '' }}>Electricity</option>
                        <option value="equipment" {{ old('category', $expense->category) == 'equipment' ? 'selected' : '' }}>Equipment</option>
                        <option value="maintenance" {{ old('category', $expense->category) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="other" {{ old('category', $expense->category) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Amount (Rs.) <span class="text-danger">*</span></label>
                    <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror"
                        value="{{ old('amount', $expense->amount) }}" step="0.01">
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Expense Date <span class="text-danger">*</span></label>
                    <input type="date" name="expense_date" class="form-control @error('expense_date') is-invalid @enderror"
                        value="{{ old('expense_date', $expense->expense_date) }}">
                    @error('expense_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Paid To</label>
                    <input type="text" name="paid_to" class="form-control"
                        value="{{ old('paid_to', $expense->paid_to) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Added By</label>
                    <select name="added_by" class="form-select">
                        <option value="">-- Select Staff --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('added_by', $expense->added_by) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $expense->notes) }}</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Update Expense
                    </button>
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary ms-2">
                        <i class="bi bi-x-lg"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection