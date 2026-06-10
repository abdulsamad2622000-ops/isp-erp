<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $modules = [
        'customers', 'connections', 'areas', 'packages',
        'invoices', 'payments', 'complaints', 'suspensions',
        'inventory', 'expenses', 'reports', 'notifications'
    ];

    public function index()
    {
        $users = User::where('role', '!=', 'admin')->with('permissions')->latest()->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $modules = $this->modules;
        return view('users.create', compact('modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        // Save permissions
        foreach ($this->modules as $module) {
            Permission::create([
                'user_id'    => $user->id,
                'module'     => $module,
                'can_view'   => $request->has("permissions.{$module}.can_view"),
                'can_add'    => $request->has("permissions.{$module}.can_add"),
                'can_edit'   => $request->has("permissions.{$module}.can_edit"),
                'can_delete' => $request->has("permissions.{$module}.can_delete"),
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        $modules = $this->modules;
        $permissions = $user->permissions->keyBy('module');
        return view('users.edit', compact('user', 'modules', 'permissions'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $data = $request->only('name', 'email');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);

        // Update permissions
        foreach ($this->modules as $module) {
            Permission::updateOrCreate(
                ['user_id' => $user->id, 'module' => $module],
                [
                    'can_view'   => $request->has("permissions.{$module}.can_view"),
                    'can_add'    => $request->has("permissions.{$module}.can_add"),
                    'can_edit'   => $request->has("permissions.{$module}.can_edit"),
                    'can_delete' => $request->has("permissions.{$module}.can_delete"),
                ]
            );
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }

    public function show(User $user)
    {
        return redirect()->route('users.index');
    }
}