<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'city',
        'area_name',
        'sub_area',
        'coverage_details',
        'is_active',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function connections()
    {
        return $this->hasMany(Connection::class);
    }
}