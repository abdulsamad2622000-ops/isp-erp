@extends('layouts.app')

@section('title', 'Add User')

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-person-plus me-2"></i>Add New User</div>
    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="Enter username">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" name="password" id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Enter password">
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePass()">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')<div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Permissions Table -->
            <div class="card">
                <div class="card-header fw-bold">
                    <i class="bi bi-shield-check me-2"></i>Module Permissions
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">Module</th>
                                    <th class="text-center">
                                        <input type="checkbox" onchange="toggleAll('can_view', this.checked)"> View
                                    </th>
                                    <th class="text-center">
                                        <input type="checkbox" onchange="toggleAll('can_add', this.checked)"> Add
                                    </th>
                                    <th class="text-center">
                                        <input type="checkbox" onchange="toggleAll('can_edit', this.checked)"> Edit
                                    </th>
                                    <th class="text-center">
                                        <input type="checkbox" onchange="toggleAll('can_delete', this.checked)"> Delete
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($modules as $module)
                                <tr>
                                    <td class="ps-3 fw-semibold">{{ ucfirst($module) }}</td>
                                    <td class="text-center">
                                        <input type="checkbox" name="permissions[{{ $module }}][can_view]"
                                            class="form-check-input can_view"
                                            {{ old("permissions.{$module}.can_view") ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="permissions[{{ $module }}][can_add]"
                                            class="form-check-input can_add"
                                            {{ old("permissions.{$module}.can_add") ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="permissions[{{ $module }}][can_edit]"
                                            class="form-check-input can_edit"
                                            {{ old("permissions.{$module}.can_edit") ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="permissions[{{ $module }}][can_delete]"
                                            class="form-check-input can_delete"
                                            {{ old("permissions.{$module}.can_delete") ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Save User
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function togglePass() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('eyeIcon');
    input.type  = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}

function toggleAll(cls, checked) {
    document.querySelectorAll('.' + cls).forEach(el => el.checked = checked);
}
</script>
@endsection