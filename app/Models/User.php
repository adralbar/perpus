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

    protected $hidden = [
        'password',
    ];
    protected $fillable = [
        'npk',
        'nama',
        'password',
        'no_telp',
        'section_id',
        'department_id',
        'division_id',
        'role_id'
    ];

    public function section()
    {
        return $this->belongsTo(SectionModel::class, 'section_id');
    }

    public function department()
    {
        return $this->belongsTo(DepartmentModel::class);
    }

    public function division()
    {
        return $this->belongsTo(DivisionModel::class);
    }

    public function role()
    {
        return $this->belongsTo(RoleModel::class);
    }
}
