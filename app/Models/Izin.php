<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    use HasFactory;

    protected $table = 'izins';

    /**
     * Kolom yang dapat diisi (Tanpa kolom status)
     */
    protected $fillable = [
        'karyawan_id',
        'jenis_izin',
        'tanggal_izin',
        'keterangan',
        'dokumen',
    ];
    protected $appends = ['dokumen_url'];

    public function getDokumenUrlAttribute()
    {
        return $this->dokumen
        ? url('dokumen_izin/' . basename($this->dokumen))
        : null;
    }

    /**
     * Relasi ke Karyawan untuk mengambil nama
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
}