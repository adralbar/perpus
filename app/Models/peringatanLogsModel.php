<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class peringatanLogsModel extends Model
{
    use HasFactory;
    protected $table = 'peringatan';


    protected $fillable = ['user_id', 'kategori', 'jumlah'];
}
