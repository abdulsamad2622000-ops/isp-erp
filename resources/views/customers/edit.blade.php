@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-pencil me-2"></i>Edit Customer — {{ $customer->name }}
    </div>
    <div class="card-body">
        <form action="{{ route('customers.update', $customer) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">User ID <span class="text-danger">*</span></label>
                    <input type="text" name="user_id" class="form-control @error('user_id') is-invalid @enderror"
                        value="{{ old('user_id', $customer->user_id) }}" placeholder="Enter User ID">
                    @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $customer->name) }}" placeholder="Enter full name">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">CNIC</label>
                    <input type="text" name="cnic" class="form-control @error('cnic') is-invalid @enderror"
                        value="{{ old('cnic', $customer->cnic) }}" placeholder="e.g. 42101-1234567-1">
                    @error('cnic')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                        value="{{ old('phone', $customer->phone) }}" placeholder="e.g. 0300-1234567">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">WhatsApp</label>
                    <input type="text" name="whatsapp" class="form-control"
                        value="{{ old('whatsapp', $customer->whatsapp) }}" placeholder="e.g. 0300-1234567">
                </div>
                <div class="col-md-6" style="position:relative;">
                    <label class="form-label fw-semibold">Area <span class="text-danger">*</span></label>
                    <input type="text" id="area_input" name="area_name"
                        class="form-control @error('area_id') is-invalid @enderror"
                        value="{{ old('area_name', $customer->area->area_name ?? '') }}"
                        placeholder="Type area name..." autocomplete="off">
                    <input type="hidden" name="area_id" id="area_id" value="{{ old('area_id', $customer->area_id) }}">
                    <ul id="area_suggestions" class="list-group shadow"
                        style="position:absolute;z-index:999;width:100%;display:none;max-height:200px;overflow-y:auto;"></ul>
                    @error('area_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Address</label>
                    <textarea name="address" class="form-control" rows="2"
                        placeholder="Enter full address">{{ old('address', $customer->address) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Due Date <span class="text-danger">*</span></label>
                    <input type="date" name="due_date" id="due_date"
                        class="form-control @error('due_date') is-invalid @enderror"
                        value="{{ old('due_date', $customer->due_date) }}">
                    @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Expiry Date</label>
                    <input type="date" name="expiry_date" id="expiry_date"
                        class="form-control"
                        value="{{ old('expiry_date', $customer->expiry_date) }}">
                    <div class="form-text">Auto set to 1 month after due date</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="active" {{ old('status', $customer->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ old('status', $customer->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="terminated" {{ old('status', $customer->status) == 'terminated' ? 'selected' : '' }}>Terminated</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Update Customer
                    </button>
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary ms-2">
                        <i class="bi bi-x-lg"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
const areas = @json($areas->map(fn($a) => ['id' => $a->id, 'name' => $a->area_name]));

const input = document.getElementById('area_input');
const hiddenInput = document.getElementById('area_id');
const suggestions = document.getElementById('area_suggestions');

input.addEventListener('input', function () {
    const query = this.value.toLowerCase().trim();
    suggestions.innerHTML = '';
    hiddenInput.value = '';

    if (query.length === 0) {
        suggestions.style.display = 'none';
        return;
    }

    const filtered = areas.filter(a => a.name.toLowerCase().includes(query));

    if (filtered.length === 0) {
        suggestions.style.display = 'none';
        return;
    }

    filtered.forEach(area => {
        const li = document.createElement('li');
        li.className = 'list-group-item list-group-item-action';
        li.style.cursor = 'pointer';
        li.textContent = area.name;
        li.addEventListener('click', function () {
            input.value = area.name;
            hiddenInput.value = area.id;
            suggestions.style.display = 'none';
        });
        suggestions.appendChild(li);
    });

    suggestions.style.display = 'block';
});

document.addEventListener('click', function (e) {
    if (!input.contains(e.target)) {
        suggestions.style.display = 'none';
    }
});

// Auto set expiry date = due date + 1 month
document.getElementById('due_date').addEventListener('change', function() {
    const due = new Date(this.value);
    if (!isNaN(due)) {
        due.setMonth(due.getMonth() + 1);
        document.getElementById('expiry_date').value = due.toISOString().split('T')[0];
    }
});


// Set expiry on page load if empty
window.addEventListener('load', function() {
    const dueInput = document.getElementById('due_date');
    const expiryInput = document.getElementById('expiry_date');
    if (dueInput.value && !expiryInput.value) {
        const due = new Date(dueInput.value);
        due.setMonth(due.getMonth() + 1);
        expiryInput.value = due.toISOString().split('T')[0];
    }
});
</script>
@endsection