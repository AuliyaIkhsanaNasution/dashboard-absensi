<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Karyawan extends Model
{
    use HasFactory;

    // Nama tabel secara eksplisit (opsional tapi disarankan)
    protected $table = 'karyawans';

    // Sesuaikan fillable dengan input yang Anda miliki di database
    protected $fillable = [
        'perusahaan_id',
        'foto',
        'nip', 
        'nama', 
        'jabatan', 
        'no_wa', 
        'status'
    ];

    /**
     * Relasi: Satu Karyawan memiliki banyak data Absensi.
     * Ini memungkinkan kita memanggil $karyawan->absensis
     */
    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'karyawan_id');
    }

     public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }

    /**
     * Helper: Menghitung total kehadiran bulan ini untuk statistik dashboard
     */
    public function totalHadirBulanIni()
    {
        return $this->absensis()
            ->where('status', 'Hadir')
            ->whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->count();
    }
}