<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Customer::with('area')->latest()->get();
    }

    public function headings(): array
    {
        return [
            '#', 'Name', 'CNIC', 'Phone', 'WhatsApp',
            'Email', 'Address', 'Area', 'Status', 'Joining Date',
        ];
    }

    public function map($customer): array
    {
        static $index = 0;
        $index++;
        return [
            $index,
            $customer->name,
            $customer->cnic ?? '',
            $customer->phone,
            $customer->whatsapp ?? '',
            $customer->email ?? '',
            $customer->address,
            $customer->area->area_name ?? 'N/A',
            ucfirst($customer->status),
            $customer->joining_date,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}