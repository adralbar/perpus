<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class daftarBukuModel extends Model
{

    protected $table = 'daftarbuku';

    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tanggal',
        'nomorisbn',
        'bahasa',
        'kategori',
        'ringkasan',
        'foto',
    ];
}
