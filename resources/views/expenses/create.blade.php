 
@extends('layouts.app')

@section('title', 'Add Expense')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-wallet2 me-2"></i>Add New Expense
    </div>
    <div class="card-body">
        <form action="{{ route('expenses.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title') }}" placeholder="e.g. Office Rent">
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror">
                        <option value="salary" {{ old('category') == 'salary' ? 'selected' : '' }}>Salary</option>
                        <option value="rent" {{ old('category') == 'rent' ? 'selected' : '' }}>Rent</option>
                        <option value="electricity" {{ old('category') == 'electricity' ? 'selected' : '' }}>Electricity</option>
                        <option value="equipment" {{ old('category') == 'equipment' ? 'selected' : '' }}>Equipment</option>
                        <option value="maintenance" {{ old('category') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Amount (Rs.) <span class="text-danger">*</span></label>
                    <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror"
                        value="{{ old('amount') }}" placeholder="0.00" step="0.01">
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Expense Date <span class="text-danger">*</span></label>
                    <input type="date" name="expense_date" class="form-control @error('expense_date') is-invalid @enderror"
                        value="{{ old('expense_date', date('Y-m-d')) }}">
                    @error('expense_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Paid To</label>
                    <input type="text" name="paid_to" class="form-control"
                        value="{{ old('paid_to') }}" placeholder="Person or company name">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Added By</label>
                    <select name="added_by" class="form-select">
                        <option value="">-- Select Staff --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('added_by') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Notes</label>
                    <textarea name="notes" class="form-control" rows="2"
                        placeholder="Optional notes">{{ old('notes') }}</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Save Expense
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