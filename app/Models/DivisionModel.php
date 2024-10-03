<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DivisionModel extends Model
{
    protected $table = 'division';

    protected $fillable = ['nama'];

    public function departments()
    {
        return $this->hasMany(DepartmentModel::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
