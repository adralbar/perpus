<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shift extends Model
{
    use HasFactory;
  protected $connection = 'mysql';
    protected $table = 'kategorishift';
    protected $fillable = ['npk', 'shift1', 'date', 'start_date', 'end_date', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'npk', 'npk');
    }
}
