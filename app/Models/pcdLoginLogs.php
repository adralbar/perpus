<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PcdLoginLogs extends Model
{
    use HasFactory;
 protected $connection = 'mysql2'; 
    protected $table = 'pcd_login_logs'; // Nama tabel di database

    // Tentukan atribut yang dapat diisi
    protected $fillable = [
        'user_id',
        'station_id',
        'status',
        'created_at',
        'updated_at',
    ];
}
