<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecapAbsensi extends Model
{
    use HasFactory;

    protected $table = 'recap_absensi';

    protected $fillable = [
        'nama',
        'npk',
        'tanggal',
        'waktuci',
        'waktuco',
        'shift1',
        'section_nama',
        'department_nama',
        'division_nama',
        'status',
        'npk_sistem'
    ];
}
