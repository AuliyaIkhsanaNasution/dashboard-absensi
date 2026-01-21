<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lembur extends Model
{
    use HasFactory;

    protected $table = 'lemburs';

    protected $fillable = [
        'karyawan_id',
        'tgl_lembur',
        'jam_mulai',
        'jam_selesai',
        'kategori',
        'keterangan',
        'dokumen',
        'status'
    ];

    protected $casts = [
        'tgl_lembur' => 'date',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    // Opsional: durasi lembur (menit)
    public function getDurasiAttribute()
    {
        if (!$this->jam_mulai || !$this->jam_selesai) {
            return null;
        }

        $mulai = \Carbon\Carbon::createFromTimeString($this->jam_mulai);
        $selesai = \Carbon\Carbon::createFromTimeString($this->jam_selesai);

        return $mulai->diffInMinutes($selesai);
    }
}
