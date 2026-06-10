<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendAutoNotifications extends Command
{
    protected $signature = 'notifications:auto-send';
    protected $description = 'Auto generate notifications for expiry, unpaid invoices, etc.';

    public function handle()
    {
        $today = Carbon::today();
        $count = 0;

        // 1. Expiry 3 din pehle
        $expiring3 = Customer::where('status', 'active')
            ->whereDate('expiry_date', $today->copy()->addDays(3))
            ->get();

        foreach ($expiring3 as $customer) {
            $exists = Notification::where('customer_id', $customer->id)
                ->where('type', 'bill_reminder')
                ->whereDate('created_at', $today)
                ->exists();
            if ($exists) continue;

            Notification::create([
                'customer_id' => $customer->id,
                'title'       => 'Package Expiring in 3 Days',
                'message'     => "Dear {$customer->name}, your package expires on " . Carbon::parse($customer->expiry_date)->format('d M Y') . ". Please renew to avoid disconnection.",
                'type'        => 'bill_reminder',
                'channel'     => 'whatsapp',
                'is_sent'     => false,
            ]);
            $count++;
        }

        // 2. Expiry 1 din pehle
        $expiring1 = Customer::where('status', 'active')
            ->whereDate('expiry_date', $today->copy()->addDays(1))
            ->get();

        foreach ($expiring1 as $customer) {
            $exists = Notification::where('customer_id', $customer->id)
                ->where('type', 'suspension_warning')
                ->whereDate('created_at', $today)
                ->exists();
            if ($exists) continue;

            Notification::create([
                'customer_id' => $customer->id,
                'title'       => 'Package Expiring Tomorrow!',
                'message'     => "Dear {$customer->name}, your package expires TOMORROW on " . Carbon::parse($customer->expiry_date)->format('d M Y') . ". Renew NOW to avoid disconnection.",
                'type'        => 'suspension_warning',
                'channel'     => 'whatsapp',
                'is_sent'     => false,
            ]);
            $count++;
        }

        // 3. Aaj expire ho rahe hain
        $expiringToday = Customer::where('status', 'active')
            ->whereDate('expiry_date', $today)
            ->get();

        foreach ($expiringToday as $customer) {
            $exists = Notification::where('customer_id', $customer->id)
                ->where('title', 'Package Expired Today')
                ->whereDate('created_at', $today)
                ->exists();
            if ($exists) continue;

            Notification::create([
                'customer_id' => $customer->id,
                'title'       => 'Package Expired Today',
                'message'     => "Dear {$customer->name}, your package has expired today. Please pay your bill immediately to restore service.",
                'type'        => 'suspension_warning',
                'channel'     => 'whatsapp',
                'is_sent'     => false,
            ]);
            $count++;
        }

        // 4. Unpaid invoice 7 din se zyada
        $unpaidInvoices = Invoice::with('customer')
            ->whereIn('status', ['unpaid', 'partial'])
            ->where('due_date', '<', $today->copy()->subDays(7))
            ->get();

        foreach ($unpaidInvoices as $invoice) {
            if (!$invoice->customer) continue;

            $exists = Notification::where('customer_id', $invoice->customer_id)
                ->where('type', 'bill_reminder')
                ->whereDate('created_at', $today)
                ->exists();
            if ($exists) continue;

            Notification::create([
                'customer_id' => $invoice->customer_id,
                'title'       => 'Overdue Invoice Reminder',
                'message'     => "Dear {$invoice->customer->name}, your invoice #{$invoice->invoice_number} of Rs. {$invoice->total_amount} is overdue. Please pay immediately.",
                'type'        => 'bill_reminder',
                'channel'     => 'whatsapp',
                'is_sent'     => false,
            ]);
            $count++;
        }

        // 5. Credit invoice 7 din se zyada
        $creditInvoices = Invoice::with('customer')
            ->where('status', 'credit')
            ->where('issue_date', '<', $today->copy()->subDays(7))
            ->get();

        foreach ($creditInvoices as $invoice) {
            if (!$invoice->customer) continue;

            $exists = Notification::where('customer_id', $invoice->customer_id)
                ->where('title', 'Credit Payment Reminder')
                ->whereDate('created_at', $today)
                ->exists();
            if ($exists) continue;

            Notification::create([
                'customer_id' => $invoice->customer_id,
                'title'       => 'Credit Payment Reminder',
                'message'     => "Dear {$invoice->customer->name}, you have a credit payment of Rs. {$invoice->total_amount} pending. Please pay at your earliest.",
                'type'        => 'bill_reminder',
                'channel'     => 'whatsapp',
                'is_sent'     => false,
            ]);
            $count++;
        }

        $this->info("Generated {$count} notifications.");
        return 0;
    }
}