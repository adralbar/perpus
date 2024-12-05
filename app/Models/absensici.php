<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class absensici extends Model
{
    protected $table = 'absensici';
    protected $fillable = ['npk_sistem', 'npk', 'tanggal', 'waktuci'];


    public function shift()
    {
        return $this->hasOne(shift::class, 'date', 'tanggal');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'npk', 'npk');
    }
    public function userByNpkSistem()
    {
        return $this->belongsTo(User::class, 'npk_sistem', 'npk_sistem');
    }
}
