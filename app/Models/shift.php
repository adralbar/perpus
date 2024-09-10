<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shift extends Model
{
    protected $table = 'kategorishift'; // Nama tabel yang baru
    protected $primaryKey = 'npk';
    public $incrementing = false;
    protected $fillable = ['npk', 'nama', 'divisi', 'departement', 'section', 'shift1', 'tanggal', 'status'];
}
