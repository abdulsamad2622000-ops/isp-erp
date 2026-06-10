<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['customer', 'package', 'payments'])->latest();

        if ($request->filled('expiry_days')) {
            $days = (int) $request->expiry_days;
            $today = now()->toDateString();
            $targetDate = now()->addDays($days)->toDateString();

            $query->whereIn('status', ['unpaid', 'partial'])
                  ->whereDate('due_date', '>=', $today)
                  ->whereDate('due_date', '<=', $targetDate);
        }

        $invoices = $query->paginate(20)->withQueryString();

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')->get();
        $packages = Package::where('is_active', true)->get();
        return view('invoices.create', compact('customers', 'packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'package_id'  => 'required|exists:packages,id',
            'amount'      => 'required|numeric|min:0',
            'discount'    => 'nullable|numeric|min:0',
            'tax'         => 'nullable|numeric|min:0',
            'issue_date'  => 'required|date',
            'due_date'    => 'required|date',
            'status'      => 'required|in:unpaid,paid,partial,overdue,credit',
            'notes'       => 'nullable|string',
        ]);

        $amount       = $request->amount;
        $discount     = $request->discount ?? 0;
        $tax          = $request->tax ?? 0;
        $total_amount = $amount - $discount + $tax;

        Invoice::create([
            'invoice_number' => 'INV-' . strtoupper(uniqid()),
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
        $packages = Package::where('is_active', true)->get();
        return view('invoices.edit', compact('invoice', 'customers', 'packages'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'package_id'  => 'required|exists:packages,id',
            'amount'      => 'required|numeric|min:0',
            'discount'    => 'nullable|numeric|min:0',
            'tax'         => 'nullable|numeric|min:0',
            'issue_date'  => 'required|date',
            'due_date'    => 'required|date',
            'status'      => 'required|in:unpaid,paid,partial,overdue,credit',
            'notes'       => 'nullable|string',
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

    public function markPaid(Invoice $invoice)
    {
        $invoice->update(['status' => 'paid']);

        Payment::create([
            'invoice_id'     => $invoice->id,
            'customer_id'    => $invoice->customer_id,
            'amount_paid'    => $invoice->total_amount,
            'payment_date'   => now()->toDateString(),
            'payment_method' => 'cash',
            'received_by'    => auth()->id(),
            'notes'          => 'Full payment received',
        ]);

        $newDueDate    = Carbon::parse($invoice->due_date);
        $newExpiryDate = $newDueDate->copy()->addMonth()->toDateString();

        $invoice->customer->update([
            'due_date'    => $newDueDate->toDateString(),
            'expiry_date' => $newExpiryDate,
        ]);

        return back()->with('success', 'Invoice paid! Customer renewed till ' . $newExpiryDate);
    }

    public function markCredit(Invoice $invoice)
    {
        $invoice->update(['status' => 'credit']);

        $newDueDate    = Carbon::parse($invoice->due_date);
        $newExpiryDate = $newDueDate->copy()->addMonth()->toDateString();

        $invoice->customer->update([
            'status'      => 'active',
            'due_date'    => $newDueDate->toDateString(),
            'expiry_date' => $newExpiryDate,
        ]);

        return back()->with('success', 'Customer activated on credit! Payment pending. Expiry: ' . $newExpiryDate);
    }

    public function partialPayment(Request $request, Invoice $invoice)
    {
        $request->validate([
            'amount_paid'    => 'required|numeric|min:1|max:' . $invoice->total_amount,
            'payment_method' => 'required|in:cash,bank_transfer,easypaisa,jazzcash,cheque',
            'notes'          => 'nullable|string',
        ]);

        $totalPaid = $invoice->payments()->sum('amount_paid') + $request->amount_paid;

        Payment::create([
            'invoice_id'     => $invoice->id,
            'customer_id'    => $invoice->customer_id,
            'amount_paid'    => $request->amount_paid,
            'payment_date'   => now()->toDateString(),
            'payment_method' => $request->payment_method,
            'received_by'    => auth()->id(),
            'notes'          => $request->notes,
        ]);

        if ($totalPaid >= $invoice->total_amount) {
            $invoice->update(['status' => 'paid']);

            $newDueDate    = Carbon::parse($invoice->due_date);
            $newExpiryDate = $newDueDate->copy()->addMonth()->toDateString();

            $invoice->customer->update([
                'due_date'    => $newDueDate->toDateString(),
                'expiry_date' => $newExpiryDate,
            ]);

            return back()->with('success', 'Full payment complete! Customer renewed till ' . $newExpiryDate);
        }

        $invoice->update(['status' => 'partial']);
        $remaining = $invoice->total_amount - $totalPaid;

        return back()->with('success', 'Partial payment of Rs.' . number_format($request->amount_paid, 0) . ' recorded. Remaining: Rs.' . number_format($remaining, 0));
    }

    public function bulkPaid(Request $request)
    {
        $request->validate([
            'invoice_ids'   => 'required|array',
            'invoice_ids.*' => 'exists:invoices,id',
        ]);

        $invoices = Invoice::with('customer')->whereIn('id', $request->invoice_ids)->get();

        foreach ($invoices as $invoice) {
            $invoice->update(['status' => 'paid']);

            Payment::create([
                'invoice_id'     => $invoice->id,
                'customer_id'    => $invoice->customer_id,
                'amount_paid'    => $invoice->total_amount,
                'payment_date'   => now()->toDateString(),
                'payment_method' => 'cash',
                'received_by'    => auth()->id(),
                'notes'          => 'Bulk payment received',
            ]);

            $newDueDate    = Carbon::parse($invoice->due_date);
            $newExpiryDate = $newDueDate->copy()->addMonth()->toDateString();

            $invoice->customer->update([
                'due_date'    => $newDueDate->toDateString(),
                'expiry_date' => $newExpiryDate,
            ]);
        }

        return back()->with('success', count($request->invoice_ids) . ' invoices paid & customers renewed!');
    }
}