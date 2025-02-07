<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class daftarPinjamModel extends Model
{

    protected $table = 'daftarpinjam';

    protected $fillable = [
        'email',
        'nama',
        'buku_id',
        'judul',
        'penulis',
        'penerbit',
        'tanggal',
        'nomorisbn',
        'bahasa',
        'kategori',
        'ringkasan',
        'foto',
        'status'
    ];
}
