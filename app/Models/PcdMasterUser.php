<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PcdMasterUser extends Model
{
    use HasFactory;

    // Nama tabel yang terkait dengan model ini
    protected $table = 'pcd_master_users';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'id',
        'name',
        'npk'
    ];
}
