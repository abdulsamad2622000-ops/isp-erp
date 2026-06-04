<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suspension extends Model
{
    protected $fillable = [
        'customer_id',
        'connection_id',
        'reason',
        'suspension_date',
        'reconnection_date',
        'status',
        'actioned_by',
        'notes',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function connection()
    {
        return $this->belongsTo(Connection::class);
    }

    public function actionedBy()
    {
        return $this->belongsTo(User::class, 'actioned_by');
    }
}