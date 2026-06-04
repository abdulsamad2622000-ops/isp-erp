<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Complaint;
use App\Models\Connection;
use App\Models\Expense;
use App\Models\Inventory;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            // Customers
            'total_customers'     => Customer::count(),
            'active_customers'    => Customer::where('status', 'active')->count(),
            'suspended_customers' => Customer::where('status', 'suspended')->count(),
            'terminated_customers'=> Customer::where('status', 'terminated')->count(),

            // Connections
            'total_connections'   => Connection::count(),
            'active_connections'  => Connection::where('status', 'active')->count(),

            // Invoices
            'total_invoices'      => Invoice::count(),
            'unpaid_invoices'     => Invoice::where('status', 'unpaid')->count(),
            'overdue_invoices'    => Invoice::where('status', 'overdue')->count(),
            'paid_invoices'       => Invoice::where('status', 'paid')->count(),

            // Payments
            'total_revenue'       => Payment::sum('amount_paid'),
            'monthly_revenue'     => Payment::whereMonth('payment_date', now()->month)
                                        ->whereYear('payment_date', now()->year)
                                        ->sum('amount_paid'),

            // Complaints
            'total_complaints'    => Complaint::count(),
            'open_complaints'     => Complaint::where('status', 'open')->count(),
            'in_progress_complaints' => Complaint::where('status', 'in_progress')->count(),

            // Expenses
            'monthly_expenses'    => Expense::whereMonth('expense_date', now()->month)
                                        ->whereYear('expense_date', now()->year)
                                        ->sum('amount'),

            // Recent Records
            'recent_customers'    => Customer::latest()->take(5)->get(),
            'recent_invoices'     => Invoice::with('customer')->latest()->take(5)->get(),
            'recent_complaints'   => Complaint::with('customer')->latest()->take(5)->get(),
            'recent_payments'     => Payment::with('customer')->latest()->take(5)->get(),
        ];

        return view('dashboard', $data);
    }
}