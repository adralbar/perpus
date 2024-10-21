<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensici extends Model
{
    protected $table = 'absensici';
    protected $fillable = ['npk', 'tanggal', 'waktuci'];

    public function user()
    {
        return $this->belongsTo(User::class, 'npk', 'npk');
    }

    public function shift()
    {
        return $this->hasOne(Shift::class, 'date', 'tanggal');
    }
}
