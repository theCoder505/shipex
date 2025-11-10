<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'otp',
        'password',
        'last_activity',
        'last_login_ip',
        'last_login_device',
        'last_login_browser',
        'last_login_location',
    ];

    protected $hidden = [
        'password',
    ];
}
