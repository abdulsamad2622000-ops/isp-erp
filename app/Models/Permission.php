<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'user_id', 'module',
        'can_view', 'can_add', 'can_edit', 'can_delete'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}