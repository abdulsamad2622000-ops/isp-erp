<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'title',
        'category',
        'amount',
        'expense_date',
        'paid_to',
        'added_by',
        'notes',
    ];

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}