<?php

namespace App\Imports;

use App\Models\Area;
use App\Models\Customer;
use OpenSpout\Reader\XLSX\Reader;
use OpenSpout\Reader\CSV\Reader as CSVReader;
use Carbon\Carbon;

class CustomersImport
{
    public function import($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'csv') {
            $reader = new CSVReader();
        } else {
            $reader = new Reader();
        }

        $reader->open($file->getPathname());

        $isFirstRow = true;
        $headers = [];

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $rowData = $row->toArray();

                // First row = headers — normalize karo
                if ($isFirstRow) {
                    $headers = array_map(function($h) {
                        return strtolower(trim(str_replace([' ', '-'], '_', $h)));
                    }, $rowData);
                    $isFirstRow = false;
                    continue;
                }

                // Skip empty rows
                if (empty(array_filter($rowData))) continue;

                // Map headers to values
                $data = [];
                foreach ($headers as $i => $header) {
                    $data[$header] = $rowData[$i] ?? null;
                }

                // Skip if name or phone missing
                if (empty($data['name']) || empty($data['phone'])) continue;

                // Skip duplicate phone
                if (Customer::where('phone', $data['phone'])->exists()) continue;

                // Area match — both 'area' key possible
                $areaName = $data['area'] ?? null;
                $area = $areaName ? Area::where('area_name', $areaName)->first() : null;

                // Due date & expiry date
                $dueDate    = !empty($data['due_date']) ? $data['due_date'] : now()->toDateString();
                $expiryDate = !empty($data['expiry_date']) ? $data['expiry_date'] : Carbon::parse($dueDate)->addMonth()->toDateString();

                // user_id — could be 'user_id' or '#' column (skip # column)
                $userId = $data['user_id'] ?? null;

                // Skip duplicate user_id
                if ($userId && Customer::where('user_id', $userId)->exists()) continue;

                Customer::create([
                    'user_id'     => $userId,
                    'name'        => $data['name'],
                    'cnic'        => $data['cnic'] ?? null,
                    'phone'       => $data['phone'],
                    'whatsapp'    => $data['whatsapp'] ?? null,
                    'email'       => $data['email'] ?? null,
                    'address'     => $data['address'] ?? null,
                    'area_id'     => $area ? $area->id : null,
                    'status'      => in_array(strtolower($data['status'] ?? ''), ['active', 'suspended', 'terminated'])
                                        ? strtolower($data['status']) : 'active',
                    'due_date'    => $dueDate,
                    'expiry_date' => $expiryDate,
                ]);
            }
        }

        $reader->close();
    }
}