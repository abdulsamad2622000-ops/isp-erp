<?php

namespace App\Imports;

use App\Models\Area;
use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class CustomersImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    public function model(array $row)
    {
        $area = Area::where('area_name', $row['area'] ?? '')->first();

        if (empty($row['name']) || empty($row['phone'])) return null;
        if (Customer::where('phone', $row['phone'])->exists()) return null;

        return new Customer([
            'name'         => $row['name'],
            'cnic'         => $row['cnic'] ?? null,
            'phone'        => $row['phone'],
            'whatsapp'     => $row['whatsapp'] ?? null,
            'email'        => $row['email'] ?? null,
            'address'      => $row['address'] ?? '',
            'area_id'      => $area ? $area->id : null,
            'status'       => in_array(strtolower($row['status'] ?? ''), ['active','suspended','terminated'])
                                ? strtolower($row['status']) : 'active',
            'joining_date' => $row['joining_date'] ?? now()->toDateString(),
        ]);
    }
}