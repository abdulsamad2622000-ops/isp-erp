<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Complaint;
use App\Models\Connection;
use App\Models\Expense;
use App\Models\Notification;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_customers'      => Customer::count(),
            'active_customers'     => Customer::where('status', 'active')->count(),
            'suspended_customers'  => Customer::where('status', 'suspended')->count(),
            'terminated_customers' => Customer::where('status', 'terminated')->count(),
            'expiring_customers'   => Customer::whereBetween('expiry_date', [now()->toDateString(), now()->addDays(7)->toDateString()])->count(),

            'total_connections'  => Connection::count(),
            'active_connections' => Connection::where('status', 'active')->count(),

            'total_invoices'   => Invoice::count(),
            'unpaid_invoices'  => Invoice::where('status', 'unpaid')->count(),
            'overdue_invoices' => Invoice::where('status', 'overdue')->count(),
            'paid_invoices'    => Invoice::where('status', 'paid')->count(),
            'credit_invoices'  => Invoice::where('status', 'credit')->count(),

            'total_revenue'   => Payment::sum('amount_paid'),
            'monthly_revenue' => Payment::whereMonth('payment_date', now()->month)->whereYear('payment_date', now()->year)->sum('amount_paid'),
            'monthly_expenses' => Expense::whereMonth('expense_date', now()->month)->whereYear('expense_date', now()->year)->sum('amount'),

            'open_complaints'        => Complaint::where('status', 'open')->count(),
            'in_progress_complaints' => Complaint::where('status', 'in_progress')->count(),

            'total_notifications'   => Notification::count(),
            'pending_notifications' => Notification::where('is_sent', false)->count(),

            'recent_customers'        => Customer::with('area')->latest()->take(10)->get(),
            'recent_invoices'         => Invoice::with('customer')->latest()->take(10)->get(),
            'recent_payments'         => Payment::with('customer')->latest()->take(10)->get(),
            'recent_complaints'       => Complaint::with('customer')->latest()->take(10)->get(),
            'recent_expenses'         => Expense::whereMonth('expense_date', now()->month)->whereYear('expense_date', now()->year)->latest()->take(10)->get(),
            'expiring_customers_list' => Customer::with('area')->whereBetween('expiry_date', [now()->toDateString(), now()->addDays(7)->toDateString()])->orderBy('expiry_date')->take(10)->get(),
            'suspended_customers_list' => Customer::with('area')->whereIn('status', ['suspended','terminated'])->latest()->take(10)->get(),
            'recent_notifications'    => Notification::with('customer')->latest()->take(10)->get(),
        ];

        return view('dashboard', $data);
    }
}