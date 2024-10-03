<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dari default (plural dari nama model)
    protected $table = 'kategorishift';

    // Tentukan kolom mana saja yang bisa diisi secara massal (mass assignable)
    protected $fillable = [

        'npk',

        'shift1',
        'date', // Tanggal per hari
        'start_date', // Tanggal awal (opsional, jika ingin tetap disimpan)
        'end_date',
        'status'
    ];

    // Jika ingin menggunakan format tanggal tertentu, bisa gunakan ini (opsional)
    protected $dates = ['start_date', 'end_date', 'date'];
}
