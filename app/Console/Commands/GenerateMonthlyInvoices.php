<?php

namespace App\Console\Commands;

use App\Models\Connection;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateMonthlyInvoices extends Command
{
    protected $signature = 'invoices:generate-monthly';
    protected $description = 'Auto generate monthly invoices for active connections';

    public function handle()
    {
        $today = Carbon::today();

        $connections = Connection::with(['customer', 'package'])
            ->where('status', 'active')
            ->whereNotNull('renewal_date')
            ->get();

        $generated = 0;

        foreach ($connections as $connection) {
            $renewalDate = Carbon::parse($connection->renewal_date);

            // Check karo agar aaj renewal date hai
            if ($renewalDate->day !== $today->day) {
                continue;
            }

            // Check karo is month invoice already bani hai ya nahi
            $exists = Invoice::where('customer_id', $connection->customer_id)
                ->where('package_id', $connection->package_id)
                ->whereMonth('issue_date', $today->month)
                ->whereYear('issue_date', $today->year)
                ->exists();

            if ($exists) {
                continue;
            }

            // Invoice number generate karo
            $invoiceNumber = 'INV-' . strtoupper(uniqid());

            $amount = $connection->package->price;
            $dueDate = $today->copy()->addMonth();

            Invoice::create([
                'invoice_number' => $invoiceNumber,
                'customer_id'    => $connection->customer_id,
                'package_id'     => $connection->package_id,
                'amount'         => $amount,
                'discount'       => 0,
                'tax'            => 0,
                'total_amount'   => $amount,
                'issue_date'     => $today->toDateString(),
                'due_date'       => $dueDate->toDateString(),
                'status'         => 'unpaid',
                'notes'          => 'Auto generated monthly invoice',
            ]);

            // Renewal date next month same date update karo
            $connection->update([
                'renewal_date' => $dueDate->toDateString(),
            ]);

            $generated++;
        }

        $this->info("Generated {$generated} invoices.");
        return 0;
    }
}