<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Package;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('customer', 'package')->latest()->paginate(15);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')->get();
        $packages  = Package::where('is_active', true)->get();
        return view('invoices.create', compact('customers', 'packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'    => 'required|exists:customers,id',
            'package_id'     => 'required|exists:packages,id',
            'amount'         => 'required|numeric|min:0',
            'discount'       => 'nullable|numeric|min:0',
            'tax'            => 'nullable|numeric|min:0',
            'issue_date'     => 'required|date',
            'due_date'       => 'required|date',
            'status'         => 'required|in:unpaid,paid,partial,overdue',
            'notes'          => 'nullable|string',
        ]);

        $amount       = $request->amount;
        $discount     = $request->discount ?? 0;
        $tax          = $request->tax ?? 0;
        $total_amount = $amount - $discount + $tax;

        $invoice_number = 'INV-' . date('Ymd') . '-' . str_pad(Invoice::count() + 1, 4, '0', STR_PAD_LEFT);

        Invoice::create([
            'invoice_number' => $invoice_number,
            'customer_id'    => $request->customer_id,
            'package_id'     => $request->package_id,
            'amount'         => $amount,
            'discount'       => $discount,
            'tax'            => $tax,
            'total_amount'   => $total_amount,
            'issue_date'     => $request->issue_date,
            'due_date'       => $request->due_date,
            'status'         => $request->status,
            'notes'          => $request->notes,
        ]);

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully!');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('customer', 'package', 'payments');
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $customers = Customer::where('status', 'active')->get();
        $packages  = Package::where('is_active', true)->get();
        return view('invoices.edit', compact('invoice', 'customers', 'packages'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'customer_id'    => 'required|exists:customers,id',
            'package_id'     => 'required|exists:packages,id',
            'amount'         => 'required|numeric|min:0',
            'discount'       => 'nullable|numeric|min:0',
            'tax'            => 'nullable|numeric|min:0',
            'issue_date'     => 'required|date',
            'due_date'       => 'required|date',
            'status'         => 'required|in:unpaid,paid,partial,overdue',
            'notes'          => 'nullable|string',
        ]);

        $amount       = $request->amount;
        $discount     = $request->discount ?? 0;
        $tax          = $request->tax ?? 0;
        $total_amount = $amount - $discount + $tax;

        $invoice->update([
            'customer_id'  => $request->customer_id,
            'package_id'   => $request->package_id,
            'amount'       => $amount,
            'discount'     => $discount,
            'tax'          => $tax,
            'total_amount' => $total_amount,
            'issue_date'   => $request->issue_date,
            'due_date'     => $request->due_date,
            'status'       => $request->status,
            'notes'        => $request->notes,
        ]);

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully!');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully!');
    }
}