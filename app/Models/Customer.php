<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'cnic',
        'phone',
        'whatsapp',
        'email',
        'address',
        'area_id',
        'status',
        'due_date',
        'expiry_date',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function connections()
    {
        return $this->hasMany(Connection::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function suspensions()
    {
        return $this->hasMany(Suspension::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}