<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'assigned_to');
    }

    public function connections()
    {
        return $this->hasMany(Connection::class, 'technician_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'received_by');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'added_by');
    }

    public function suspensions()
    {
        return $this->hasMany(Suspension::class, 'actioned_by');
    }
}