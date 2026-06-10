<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateMonthlyInvoices extends Command
{
    protected $signature = 'invoices:generate-monthly';
    protected $description = 'Auto generate monthly invoices on customer due date';

    public function handle()
    {
        $today = Carbon::today();

        $customers = Customer::with('connections.package')
            ->where('status', 'active')
            ->whereNotNull('due_date')
            ->whereDate('due_date', $today)
            ->get();

        $generated = 0;

        foreach ($customers as $customer) {
            // Is month invoice already bani hai?
            $exists = Invoice::where('customer_id', $customer->id)
                ->whereMonth('issue_date', $today->month)
                ->whereYear('issue_date', $today->year)
                ->exists();

            if ($exists) continue;

            // Package — active connection se lo
            $connection = $customer->connections->where('status', 'active')->first();
            $package = $connection ? $connection->package : Package::where('is_active', true)->first();

            if (!$package) continue;

            Invoice::create([
                'invoice_number' => 'INV-' . strtoupper(uniqid()),
                'customer_id'    => $customer->id,
                'package_id'     => $package->id,
                'amount'         => $package->price,
                'discount'       => 0,
                'tax'            => 0,
                'total_amount'   => $package->price,
                'issue_date'     => $today->toDateString(),
                'due_date'       => $customer->expiry_date,
                'status'         => 'unpaid',
                'notes'          => 'Auto generated monthly invoice',
            ]);

            $generated++;
        }

        $this->info("Generated {$generated} invoices.");
        return 0;
    }
}