<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class absensico extends Model
{
    public $table = "absensico";
    protected $fillable = ['nama', 'npk', 'tanggal', 'status', 'bukti', 'waktuco'];
}
