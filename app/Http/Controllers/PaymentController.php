<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('customer', 'invoice')->latest()->paginate(15);
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')->get();
        $invoices  = Invoice::where('status', '!=', 'paid')->with('customer')->get();
        $users     = User::all();
        return view('payments.create', compact('customers', 'invoices', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_id'     => 'required|exists:invoices,id',
            'customer_id'    => 'required|exists:customers,id',
            'amount_paid'    => 'required|numeric|min:0',
            'payment_date'   => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,easypaisa,jazzcash,cheque',
            'transaction_id' => 'nullable|string|max:100',
            'received_by'    => 'nullable|exists:users,id',
            'notes'          => 'nullable|string',
        ]);

        Payment::create($request->all());

        // Update invoice status
        $invoice      = Invoice::find($request->invoice_id);
        $total_paid   = $invoice->payments()->sum('amount_paid') + $request->amount_paid;

        if ($total_paid >= $invoice->total_amount) {
            $invoice->update(['status' => 'paid']);
        } else {
            $invoice->update(['status' => 'partial']);
        }

        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully!');
    }

    public function show(Payment $payment)
    {
        $payment->load('customer', 'invoice', 'receivedBy');
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $customers = Customer::where('status', 'active')->get();
        $invoices  = Invoice::with('customer')->get();
        $users     = User::all();
        return view('payments.edit', compact('payment', 'customers', 'invoices', 'users'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'invoice_id'     => 'required|exists:invoices,id',
            'customer_id'    => 'required|exists:customers,id',
            'amount_paid'    => 'required|numeric|min:0',
            'payment_date'   => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,easypaisa,jazzcash,cheque',
            'transaction_id' => 'nullable|string|max:100',
            'received_by'    => 'nullable|exists:users,id',
            'notes'          => 'nullable|string',
        ]);

        $payment->update($request->all());

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully!');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully!');
    }
}