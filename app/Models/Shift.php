<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $table = 'shifts';

    protected $fillable = [
        'nama_shift',
        'jam_masuk',
        'jam_pulang',
        'toleransi_menit',
        // ❌ TIDAK ADA perusahaan_id
    ];

    /**
     * Relasi ke Absensi
     */
    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'shift_id');
    }

    /**
     * Accessor untuk format jam kerja
     */
    public function getJamKerjaAttribute()
    {
        return substr($this->jam_masuk, 0, 5) . ' - ' . substr($this->jam_pulang, 0, 5);
    }

    /**
     * Method untuk cek status keterlambatan
     */
    public function cekStatus($jamAbsen)
    {
        $jamMasukShift = \Carbon\Carbon::parse($this->jam_masuk);
        $toleransi = $jamMasukShift->copy()->addMinutes($this->toleransi_menit ?? 15);
        $jamAbsenCarbon = \Carbon\Carbon::parse($jamAbsen);

        if ($jamAbsenCarbon->lte($toleransi)) {
            return 'Tepat Waktu';
        }
        
        return 'Terlambat';
    }
}