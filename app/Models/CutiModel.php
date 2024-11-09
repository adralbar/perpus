<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutiModel extends Model
{
    use HasFactory;

    protected $table = 'cuti'; // Nama tabel di basis data

    protected $fillable = [
        'section_id',
        'department_id',
        'division_id',
        'role_id',
        'npk',
        'nama',
        'kategori',
        'tanggal_mulai',
        'tanggal_selesai',
        'keperluan',
        'cuti_lainnya',
        'sent'
    ];

    // Jika Anda memiliki relasi lain, tambahkan di sini
}
