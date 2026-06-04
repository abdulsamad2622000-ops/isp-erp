<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'item_name',
        'model',
        'category',
        'total_stock',
        'available_stock',
        'assigned_stock',
        'unit_price',
        'supplier',
    ];
}