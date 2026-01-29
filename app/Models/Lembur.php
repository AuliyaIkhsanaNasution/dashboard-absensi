<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    // supaya ikut muncul di API response
    protected $appends = ['durasi', 'total_lembur'];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    /**
     * Durasi lembur dalam MENIT
     */
    public function getDurasiAttribute()
    {
        if (!$this->jam_mulai || !$this->jam_selesai) {
            return null;
        }

        $mulai   = Carbon::parse($this->jam_mulai);
        $selesai = Carbon::parse($this->jam_selesai);

        // lembur lewat tengah malam
        if ($selesai->lessThan($mulai)) {
            $selesai->addDay();
        }

        return $mulai->diffInMinutes($selesai);
    }

    /**
     * Total lembur dalam JAM (decimal)
     */
    public function getTotalLemburAttribute()
    {
        if ($this->durasi === null) {
            return null;
        }

        return round($this->durasi / 60, 2);
    }
}