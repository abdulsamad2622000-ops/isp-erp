<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Area;
use App\Models\Package;
use App\Models\Connection;
use App\Exports\CustomersExport;
use App\Imports\CustomersImport;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with('area')->latest();

        if ($request->filled('expiry_days')) {
            $days = (int) $request->expiry_days;
            $targetDate = now()->addDays($days)->toDateString();
            $today = now()->toDateString();

            $query->whereNotNull('expiry_date')
                  ->whereDate('expiry_date', '>=', $today)
                  ->whereDate('expiry_date', '<=', $targetDate);
        }

        $customers = $query->paginate(15)->withQueryString();
        $expiryDays = $request->expiry_days;

        return view('customers.index', compact('customers', 'expiryDays'));
    }

    public function create()
    {
        $areas    = Area::where('is_active', true)->get();
        $packages = Package::where('is_active', true)->get();
        return view('customers.create', compact('areas', 'packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'phone'       => 'required|string|max:20',
            'address'     => 'nullable|string',
            'area_id'     => 'required|exists:areas,id',
            'due_date'    => 'required|date',
            'cnic'        => 'nullable|string|max:15|unique:customers,cnic',
            'email'       => 'nullable|email',
            'status'      => 'required|in:active,suspended,terminated',
            'user_id'     => 'required|string|max:255|unique:customers,user_id',
            // Connection validation (optional)
            'package_id'       => 'nullable|exists:packages,id',
            'ip_address'       => 'nullable|string|max:50',
            'mac_address'      => 'nullable|string|max:50',
            'conn_username'    => 'nullable|string|max:100',
            'conn_password'    => 'nullable|string|max:100',
            'connection_type'  => 'nullable|in:fiber,wireless,dsl',
            'installation_date'=> 'nullable|date',
        ]);

        $dueDate    = Carbon::parse($request->due_date);
        $expiryDate = $request->filled('expiry_date')
                        ? $request->expiry_date
                        : $dueDate->copy()->addMonth()->toDateString();

        $customer = Customer::create([
            'user_id'     => $request->user_id,
            'name'        => $request->name,
            'cnic'        => $request->cnic,
            'phone'       => $request->phone,
            'whatsapp'    => $request->whatsapp,
            'email'       => $request->email,
            'address'     => $request->address,
            'area_id'     => $request->area_id,
            'status'      => $request->status,
            'due_date'    => $request->due_date,
            'expiry_date' => $expiryDate,
        ]);

        // Agar connection details fill ki hain toh connection bhi banao
        if ($request->filled('package_id')) {
            Connection::create([
                'customer_id'       => $customer->id,
                'package_id'        => $request->package_id,
                'area_id'           => $request->area_id,
                'ip_address'        => $request->ip_address,
                'mac_address'       => $request->mac_address,
                'username'          => $request->conn_username,
                'password'          => $request->conn_password,
                'connection_type'   => $request->connection_type ?? 'fiber',
                'status'            => $request->status == 'active' ? 'active' : 'suspended',
                'installation_date' => $request->installation_date ?? now()->toDateString(),
            ]);
        }

        return redirect()->route('customers.index')->with('success', 'Customer added successfully!');
    }

    public function show(Customer $customer)
    {
        $customer->load('area', 'connections.package', 'invoices', 'complaints', 'payments');
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $areas    = Area::where('is_active', true)->get();
        $packages = Package::where('is_active', true)->get();
        return view('customers.edit', compact('customer', 'areas', 'packages'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'required|string|max:20',
            'address'   => 'nullable|string',
            'area_id'   => 'required|exists:areas,id',
            'due_date'  => 'required|date',
            'cnic'      => 'nullable|string|max:15|unique:customers,cnic,' . $customer->id,
            'email'     => 'nullable|email',
            'status'    => 'required|in:active,suspended,terminated',
            'user_id'   => 'required|string|max:255|unique:customers,user_id,' . $customer->id,
        ]);

        $dueDate    = Carbon::parse($request->due_date);
        $expiryDate = $request->filled('expiry_date')
                        ? $request->expiry_date
                        : $dueDate->copy()->addMonth()->toDateString();

        $customer->update([
            'user_id'     => $request->user_id,
            'name'        => $request->name,
            'cnic'        => $request->cnic,
            'phone'       => $request->phone,
            'whatsapp'    => $request->whatsapp,
            'email'       => $request->email,
            'address'     => $request->address,
            'area_id'     => $request->area_id,
            'status'      => $request->status,
            'due_date'    => $request->due_date,
            'expiry_date' => $expiryDate,
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
    }

    public function export()
    {
        return (new CustomersExport)->download();
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        (new CustomersImport)->import($request->file('file'));

        return redirect()->route('customers.index')->with('success', 'Customers imported successfully!');
    }
}