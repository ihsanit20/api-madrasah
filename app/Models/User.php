<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'is_active',
        'role',
        'phone_verified_at',
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'phone_verified_at' => 'datetime',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    protected $appends = [
        'avatar'
    ];

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn () => "https://ui-avatars.com/api/?name=" . str_replace(" ", "+", $this->name),
        );
    }
}
