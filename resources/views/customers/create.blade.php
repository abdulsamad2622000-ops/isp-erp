@extends('layouts.app')

@section('title', 'Add Customer')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-person-plus me-2"></i>Add New Customer
    </div>
    <div class="card-body">
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf

            {{-- ===== CUSTOMER INFO ===== --}}
            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-person me-2"></i>Customer Information</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">User ID <span class="text-danger">*</span></label>
                    <input type="text" name="user_id" class="form-control @error('user_id') is-invalid @enderror"
                        value="{{ old('user_id') }}" placeholder="Enter User ID">
                    @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="Enter full name">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">CNIC</label>
                    <input type="text" name="cnic" class="form-control @error('cnic') is-invalid @enderror"
                        value="{{ old('cnic') }}" placeholder="e.g. 42101-1234567-1">
                    @error('cnic')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                        value="{{ old('phone') }}" placeholder="e.g. 0300-1234567">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">WhatsApp</label>
                    <input type="text" name="whatsapp" class="form-control"
                        value="{{ old('whatsapp') }}" placeholder="e.g. 0300-1234567">
                </div>
                <div class="col-md-6" style="position:relative;">
                    <label class="form-label fw-semibold">Area <span class="text-danger">*</span></label>
                    <input type="text" id="area_input" name="area_name"
                        class="form-control @error('area_id') is-invalid @enderror"
                        value="{{ old('area_name') }}"
                        placeholder="Type area name..." autocomplete="off">
                    <input type="hidden" name="area_id" id="area_id" value="{{ old('area_id') }}">
                    <ul id="area_suggestions" class="list-group shadow"
                        style="position:absolute;z-index:999;width:100%;display:none;max-height:200px;overflow-y:auto;"></ul>
                    @error('area_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Address</label>
                    <textarea name="address" class="form-control" rows="2"
                        placeholder="Enter full address">{{ old('address') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Due Date <span class="text-danger">*</span></label>
                    <input type="date" name="due_date" id="due_date"
                        class="form-control @error('due_date') is-invalid @enderror"
                        value="{{ old('due_date', date('Y-m-d')) }}">
                    @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Expiry Date</label>
                    <input type="date" name="expiry_date" id="expiry_date"
                        class="form-control" value="{{ old('expiry_date') }}">
                    <div class="form-text">Auto set to 1 month after due date</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="active"     {{ old('status','active') == 'active'     ? 'selected' : '' }}>Active</option>
                        <option value="suspended"  {{ old('status') == 'suspended'  ? 'selected' : '' }}>Suspended</option>
                        <option value="terminated" {{ old('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- ===== CONNECTION DETAILS ===== --}}
            <div class="border rounded p-3 mb-4" style="background:#f8f9ff;">
                <h6 class="fw-bold text-success mb-3">
                    <i class="bi bi-plug me-2"></i>Connection Details
                    <small class="text-muted fw-normal ms-2">(Optional — fill karo agar abhi connection lagana hai)</small>
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Package</label>
                        <select name="package_id" class="form-select @error('package_id') is-invalid @enderror">
                            <option value="">-- Select Package --</option>
                            @foreach($packages as $pkg)
                            <option value="{{ $pkg->id }}" {{ old('package_id') == $pkg->id ? 'selected' : '' }}>
                                {{ $pkg->name }} — Rs. {{ number_format($pkg->price) }}
                            </option>
                            @endforeach
                        </select>
                        @error('package_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Connection Type</label>
                        <select name="connection_type" class="form-select">
                            <option value="fiber"    {{ old('connection_type','fiber') == 'fiber'    ? 'selected' : '' }}>Fiber</option>
                            <option value="wireless" {{ old('connection_type') == 'wireless' ? 'selected' : '' }}>Wireless</option>
                            <option value="dsl"      {{ old('connection_type') == 'dsl'      ? 'selected' : '' }}>DSL</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">IP Address</label>
                        <input type="text" name="ip_address" class="form-control"
                            value="{{ old('ip_address') }}" placeholder="e.g. 192.168.1.100">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">MAC Address</label>
                        <input type="text" name="mac_address" class="form-control"
                            value="{{ old('mac_address') }}" placeholder="e.g. AA:BB:CC:DD:EE:FF">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="conn_username" class="form-control"
                            value="{{ old('conn_username') }}" placeholder="PPPoE username">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="text" name="conn_password" class="form-control"
                            value="{{ old('conn_password') }}" placeholder="PPPoE password">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Installation Date</label>
                        <input type="date" name="installation_date" class="form-control"
                            value="{{ old('installation_date', date('Y-m-d')) }}">
                    </div>
                </div>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Save Customer
                </button>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary ms-2">
                    <i class="bi bi-x-lg"></i> Cancel
                </a>
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
    if (query.length === 0) { suggestions.style.display = 'none'; return; }
    const filtered = areas.filter(a => a.name.toLowerCase().includes(query));
    if (filtered.length === 0) { suggestions.style.display = 'none'; return; }
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
    if (!input.contains(e.target)) suggestions.style.display = 'none';
});

document.getElementById('due_date').addEventListener('change', function() {
    const due = new Date(this.value);
    if (!isNaN(due)) {
        due.setMonth(due.getMonth() + 1);
        document.getElementById('expiry_date').value = due.toISOString().split('T')[0];
    }
});

window.addEventListener('load', function() {
    const dueInput = document.getElementById('due_date');
    if (dueInput.value && !document.getElementById('expiry_date').value) {
        const due = new Date(dueInput.value);
        due.setMonth(due.getMonth() + 1);
        document.getElementById('expiry_date').value = due.toISOString().split('T')[0];
    }
});
</script>
@endsection