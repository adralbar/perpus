<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shift extends Model
{
    protected $table = 'kategorishift'; // Nama tabel yang baru
    public $incrementing = false;
    protected $fillable = ['npkSistem', 'npk', 'nama', 'divisi', 'departement', 'section', 'shift1', 'start_date', 'end_date', 'status'];
}
