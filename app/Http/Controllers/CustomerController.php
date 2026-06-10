<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Area;
use App\Exports\CustomersExport;
use App\Imports\CustomersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with('area')->latest()->paginate(15);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        $areas = Area::where('is_active', true)->get();
        return view('customers.create', compact('areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'phone'        => 'required|string|max:20',
            'address' => 'nullable|string',
            'area_id'      => 'required|exists:areas,id',
            'joining_date' => 'required|date',
            'cnic'         => 'nullable|string|max:15|unique:customers,cnic',
            'email'        => 'nullable|email',
            'status'       => 'required|in:active,suspended,terminated',
            'user_id' => 'required|string|max:255|unique:customers,user_id',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')->with('success', 'Customer added successfully!');
    }

    public function show(Customer $customer)
    {
        $customer->load('area', 'connections.package', 'invoices', 'complaints', 'payments');
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $areas = Area::where('is_active', true)->get();
        return view('customers.edit', compact('customer', 'areas'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'phone'        => 'required|string|max:20',
           'address' => 'nullable|string',
            'area_id'      => 'required|exists:areas,id',
            'joining_date' => 'required|date',
            'cnic'         => 'nullable|string|max:15|unique:customers,cnic,' . $customer->id,
            'email'        => 'nullable|email',
            'status'       => 'required|in:active,suspended,terminated',
            'user_id' => 'required|string|max:255|unique:customers,user_id,' . $customer->id,
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
    }

    public function export()
    {
        return Excel::download(new CustomersExport, 'customers_' . date('Y-m-d') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        Excel::import(new CustomersImport, $request->file('file'));

        return redirect()->route('customers.index')->with('success', 'Customers imported successfully!');
    }
}