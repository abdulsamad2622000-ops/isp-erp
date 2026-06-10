<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function hasPermission($module, $action = 'can_view')
    {
        if ($this->role === 'admin') return true;

        return $this->permissions()
            ->where('module', $module)
            ->where($action, true)
            ->exists();
    }
}