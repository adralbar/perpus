<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyimpanganModel extends Model
{
    use HasFactory;

    protected $table = 'penyimpangan';

    protected $fillable = [
        'nama',
        'npk',
        'kategori',
        'tanggal_mulai',
        'tanggal_selesai',
        'jam_mulai',
        'jam_selesai',
        'jenis_keperluan',
        'keterangan',
        'draft',
        'sent',
        'section_id',
        'department_id',
        'division_id',
        'role_id',
        'approved_by',
        'rejected_by',
        'reason',
        'foto'
    ];


    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejecter()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
    public function division()
    {
        return $this->belongsTo(DivisionModel::class);
    }

    public function department()
    {
        return $this->belongsTo(DepartmentModel::class);
    }

    public function section()
    {
        return $this->belongsTo(SectionModel::class);
    }
}
