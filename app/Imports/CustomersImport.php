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

                if ($isFirstRow) {
                    $headers = array_map(function($h) {
                        return strtolower(trim(str_replace([' ', '-'], '_', $h)));
                    }, $rowData);
                    $isFirstRow = false;
                    continue;
                }

                if (empty(array_filter($rowData))) continue;

                $data = [];
                foreach ($headers as $i => $header) {
                    $data[$header] = $rowData[$i] ?? null;
                }

                if (empty($data['name']) || empty($data['phone'])) continue;
                if (Customer::where('phone', $data['phone'])->exists()) continue;

                $areaName = $data['area'] ?? null;
                $area = $areaName ? Area::where('area_name', $areaName)->first() : null;

                // Due date & expiry date — safe parsing
                $dueDate    = $this->parseDate($data['due_date'] ?? null) ?? now()->toDateString();
                $expiryDate = $this->parseDate($data['expiry_date'] ?? null) ?? Carbon::parse($dueDate)->addMonth()->toDateString();

                $userId = $data['user_id'] ?? null;
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

    /**
     * Excel se aaye kisi bhi date format ko Y-m-d mein convert karo
     */
    private function parseDate($value)
    {
        if (empty($value)) return null;

        // Already a DateTime object (OpenSpout sometimes returns this)
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        $value = trim((string) $value);
        if ($value === '') return null;

        // Try common formats
        $formats = [
            'Y-m-d',
            'd M Y H:i:s',
            'd M Y',
            'd/m/Y',
            'm/d/Y',
            'd-m-Y',
            'Y-m-d H:i:s',
        ];

        foreach ($formats as $format) {
            try {
                $date = Carbon::createFromFormat($format, $value);
                if ($date !== false) {
                    return $date->format('Y-m-d');
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        // Last resort — Carbon ka generic parser
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}