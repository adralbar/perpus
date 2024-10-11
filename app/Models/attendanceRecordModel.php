<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class attendanceRecordModel extends Model
{
    use HasFactory;

    // Tentukan nama tabel yang digunakan oleh model ini
    protected $table = 'attendance_records';

    // Tentukan kolom yang dapat diisi
    protected $fillable = [
        'npk',
        'tanggal',
        'waktuci',
        'waktuco',
        'shift1',
        'status',
    ];

    // Jika Anda menggunakan timestamps, Anda bisa menambahkan properti ini
    public $timestamps = true; // Secara default ini adalah true

}
