<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kategoriModel extends Model
{

    protected $table = 'kategori';

    protected $fillable = [
        'id',
        'nama_kategori',
    ];
}
