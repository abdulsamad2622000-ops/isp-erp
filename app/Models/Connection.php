<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    protected $fillable = [
        'customer_id',
        'package_id',
        'area_id',
        'ip_address',
        'mac_address',
        'username',
        'password',
        'connection_type',
        'status',
        'installation_date',
        'technician_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function suspensions()
    {
        return $this->hasMany(Suspension::class);
    }
}