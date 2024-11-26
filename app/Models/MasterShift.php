<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterShift extends Model
{
    use HasFactory;

    protected $table = 'master_shift';

    protected $fillable = [
        'shift_name',
        'waktu'
    ];
}
