<?php

namespace App\Http\Controllers;

use App\Models\Suspension;
use App\Models\Customer;
use App\Models\Connection;
use App\Models\User;
use Illuminate\Http\Request;

class SuspensionController extends Controller
{
    public function index()
    {
        $suspensions = Suspension::with('customer', 'connection', 'actionedBy')->latest()->paginate(15);
        return view('suspensions.index', compact('suspensions'));
    }

    public function create()
    {
        $customers   = Customer::where('status', 'active')->get();
        $connections = Connection::where('status', 'active')->with('customer')->get();
        $users       = User::all();
        return view('suspensions.create', compact('customers', 'connections', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'     => 'required|exists:customers,id',
            'connection_id'   => 'required|exists:connections,id',
            'reason'          => 'required|in:non_payment,request,violation,other',
            'suspension_date' => 'required|date',
            'status'          => 'required|in:suspended,reconnected',
            'actioned_by'     => 'nullable|exists:users,id',
            'notes'           => 'nullable|string',
        ]);

        Suspension::create($request->all());

        // Update customer and connection status
        Customer::find($request->customer_id)->update(['status' => 'suspended']);
        Connection::find($request->connection_id)->update(['status' => 'suspended']);

        return redirect()->route('suspensions.index')->with('success', 'Suspension recorded successfully!');
    }

    public function edit(Suspension $suspension)
    {
        $customers   = Customer::all();
        $connections = Connection::with('customer')->get();
        $users       = User::all();
        return view('suspensions.edit', compact('suspension', 'customers', 'connections', 'users'));
    }

    public function update(Request $request, Suspension $suspension)
    {
        $request->validate([
            'customer_id'       => 'required|exists:customers,id',
            'connection_id'     => 'required|exists:connections,id',
            'reason'            => 'required|in:non_payment,request,violation,other',
            'suspension_date'   => 'required|date',
            'status'            => 'required|in:suspended,reconnected',
            'actioned_by'       => 'nullable|exists:users,id',
            'notes'             => 'nullable|string',
        ]);

        $suspension->update($request->all());

        // If reconnected update customer and connection status
        if ($request->status == 'reconnected') {
            Customer::find($request->customer_id)->update(['status' => 'active']);
            Connection::find($request->connection_id)->update(['status' => 'active']);
        }

        return redirect()->route('suspensions.index')->with('success', 'Suspension updated successfully!');
    }

    public function destroy(Suspension $suspension)
    {
        $suspension->delete();
        return redirect()->route('suspensions.index')->with('success', 'Suspension deleted successfully!');
    }

    public function show(Suspension $suspension)
    {
        return redirect()->route('suspensions.index');
    }
}