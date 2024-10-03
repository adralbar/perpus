<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentModel extends Model
{
    protected $table = 'department';

    protected $fillable = ['nama', 'division_id'];

    public function division()
    {
        return $this->belongsTo(DivisionModel::class, 'division_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
