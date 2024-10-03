<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectionModel extends Model
{
    protected $table = 'section';

    protected $fillable = ['nama', 'department_id'];

    public function department()
    {
        return $this->belongsTo(DepartmentModel::class, 'department_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
