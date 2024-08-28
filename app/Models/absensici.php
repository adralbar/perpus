<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class absensici extends Model
{
    public $table = "absensici";
    protected $fillable = ['nama', 'npk', 'tanggal', 'bukti', 'waktuci'];
}
