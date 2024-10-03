<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    protected $table = 'role';

    protected $fillable = ['nama'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
