<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'customer_id',
        'title',
        'message',
        'type',
        'channel',
        'is_sent',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'is_sent' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}