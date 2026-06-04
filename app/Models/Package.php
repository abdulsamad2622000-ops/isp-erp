<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name',
        'speed',
        'price',
        'validity',
        'type',
        'description',
        'is_active',
    ];

    public function connections()
    {
        return $this->hasMany(Connection::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}