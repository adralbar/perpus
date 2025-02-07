<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'users';
    protected $connection = 'mysql';

    protected $hidden = [
        'password',
    ];
    protected $fillable = [
        'id',
        'email',
        'nama',
        'password',
        'role_id',


    ];
}
