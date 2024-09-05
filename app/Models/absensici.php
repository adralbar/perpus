<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensici extends Model
{
    protected $table = 'absensici';
    protected $fillable = ['npk', 'tanggal', 'waktuci',];
}
