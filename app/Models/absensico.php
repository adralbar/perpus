<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class absensico extends Model
{
    public $table = "absensico";
    protected $fillable = ['npk', 'tanggal', 'status',  'waktuco'];

    public function user()
    {
        return $this->belongsTo(User::class, 'npk', 'npk');
    }

    public function shift()
    {
        return $this->hasOne(Shift::class, 'date', 'tanggal');
    }
}
