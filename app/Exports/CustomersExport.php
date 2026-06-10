<?php

namespace App\Exports;

use App\Models\Customer;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;

class CustomersExport
{
    public function download()
    {
        $fileName = 'customers_' . date('Y-m-d') . '.xlsx';
        $filePath = storage_path('app/' . $fileName);

        $writer = new Writer();
        $writer->openToFile($filePath);

        // Heading row
        $writer->addRow(Row::fromValues([
            'user_id', 'name', 'cnic', 'phone', 'whatsapp',
            'email', 'address', 'area', 'status', 'due_date', 'expiry_date'
        ]));

        // Data rows
        $customers = Customer::with('area')->latest()->get();
        foreach ($customers as $customer) {
            $writer->addRow(Row::fromValues([
                $customer->user_id ?? '',
                $customer->name,
                $customer->cnic ?? '',
                $customer->phone,
                $customer->whatsapp ?? '',
                $customer->email ?? '',
                $customer->address ?? '',
                $customer->area->area_name ?? '',
                $customer->status,
                $customer->due_date ?? '',
                $customer->expiry_date ?? '',
            ]));
        }

        $writer->close();

        return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
    }
}