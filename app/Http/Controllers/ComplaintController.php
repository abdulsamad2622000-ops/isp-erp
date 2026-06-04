<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with('customer', 'assignedTo')->latest()->paginate(15);
        return view('complaints.index', compact('complaints'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')->get();
        $users     = User::all();
        return view('complaints.create', compact('customers', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'category'    => 'required|in:connectivity,speed,billing,equipment,other',
            'priority'    => 'required|in:low,medium,high,critical',
            'status'      => 'required|in:open,in_progress,resolved,closed',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $data = $request->all();

        if ($request->status == 'resolved' && !$request->resolved_at) {
            $data['resolved_at'] = now();
        }

        Complaint::create($data);

        return redirect()->route('complaints.index')->with('success', 'Complaint added successfully!');
    }

    public function show(Complaint $complaint)
    {
        $complaint->load('customer', 'assignedTo');
        return view('complaints.show', compact('complaint'));
    }

    public function edit(Complaint $complaint)
    {
        $customers = Customer::where('status', 'active')->get();
        $users     = User::all();
        return view('complaints.edit', compact('complaint', 'customers', 'users'));
    }

    public function update(Request $request, Complaint $complaint)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'category'    => 'required|in:connectivity,speed,billing,equipment,other',
            'priority'    => 'required|in:low,medium,high,critical',
            'status'      => 'required|in:open,in_progress,resolved,closed',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $data = $request->all();

        if ($request->status == 'resolved' && !$complaint->resolved_at) {
            $data['resolved_at'] = now();
        }

        $complaint->update($data);

        return redirect()->route('complaints.index')->with('success', 'Complaint updated successfully!');
    }

    public function destroy(Complaint $complaint)
    {
        $complaint->delete();
        return redirect()->route('complaints.index')->with('success', 'Complaint deleted successfully!');
    }
}