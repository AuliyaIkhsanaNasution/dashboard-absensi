<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lembur extends Model
{
    use HasFactory;

    protected $fillable = [
        'karyawan_id', 'tgl_lembur', 'jam_mulai', 'jam_selesai', 'kategori', 'keterangan', 'dokumen','status'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}