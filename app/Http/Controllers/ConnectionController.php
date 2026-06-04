<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;

class ConnectionController extends Controller
{
    public function index()
    {
        $connections = Connection::with('customer', 'package', 'area')->latest()->paginate(15);
        return view('connections.index', compact('connections'));
    }

    public function create()
    {
        $customers   = Customer::where('status', 'active')->get();
        $packages    = Package::where('is_active', true)->get();
        $areas       = Area::where('is_active', true)->get();
        $technicians = User::all();
        return view('connections.create', compact('customers', 'packages', 'areas', 'technicians'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'       => 'required|exists:customers,id',
            'package_id'        => 'required|exists:packages,id',
            'area_id'           => 'required|exists:areas,id',
            'connection_type'   => 'required|in:fiber,wireless,dsl',
            'installation_date' => 'required|date',
            'status'            => 'required|in:active,suspended,terminated',
            'ip_address'        => 'nullable|string|max:20',
            'mac_address'       => 'nullable|string|max:20',
            'username'          => 'nullable|string|max:100',
            'password'          => 'nullable|string|max:100',
        ]);

        Connection::create($request->all());

        return redirect()->route('connections.index')->with('success', 'Connection added successfully!');
    }

    public function show(Connection $connection)
    {
        $connection->load('customer', 'package', 'area', 'technician');
        return view('connections.show', compact('connection'));
    }

    public function edit(Connection $connection)
    {
        $customers   = Customer::where('status', 'active')->get();
        $packages    = Package::where('is_active', true)->get();
        $areas       = Area::where('is_active', true)->get();
        $technicians = User::all();
        return view('connections.edit', compact('connection', 'customers', 'packages', 'areas', 'technicians'));
    }

    public function update(Request $request, Connection $connection)
    {
        $request->validate([
            'customer_id'       => 'required|exists:customers,id',
            'package_id'        => 'required|exists:packages,id',
            'area_id'           => 'required|exists:areas,id',
            'connection_type'   => 'required|in:fiber,wireless,dsl',
            'installation_date' => 'required|date',
            'status'            => 'required|in:active,suspended,terminated',
            'ip_address'        => 'nullable|string|max:20',
            'mac_address'       => 'nullable|string|max:20',
            'username'          => 'nullable|string|max:100',
            'password'          => 'nullable|string|max:100',
        ]);

        $connection->update($request->all());

        return redirect()->route('connections.index')->with('success', 'Connection updated successfully!');
    }

    public function destroy(Connection $connection)
    {
        $connection->delete();
        return redirect()->route('connections.index')->with('success', 'Connection deleted successfully!');
    }
}