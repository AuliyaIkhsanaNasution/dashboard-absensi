<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Absensi extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang didefinisikan di database.
     */
    protected $table = 'absensis';

    /**
     * Field yang dapat diisi secara massal (Mass Assignment).
     * Pastikan 'perusahaan_id' sudah terdaftar di sini.
     */
    protected $fillable = [
        'karyawan_id',
        'perusahaan_id', // Tambahan: Agar data perusahaan tersimpan permanen
        'tanggal',
        'shift_id',
        'jam_masuk',
        'jam_pulang',
        'foto_masuk',
        'foto_keluar',
        'lokasi_masuk',
        'lokasi_pulang',
        'status',
        'latitude',
        'longitude',
        'jarak', 
    ];

    /**
     * Menambahkan field virtual (Append) agar total_kerja 
     * muncul saat data dikonversi ke JSON.
     */
    protected $appends = ['total_kerja'];

    /**
     * Relasi ke Model Karyawan.
     * Absensi dimiliki oleh satu Karyawan.
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    /**
     * Relasi ke Model Perusahaan.
     * Absensi mencatat di perusahaan mana karyawan tersebut saat absen.
     */
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }

    /**
     * Accessor untuk menghitung Total Jam Kerja secara otomatis.
     * Menghitung selisih antara jam_masuk dan jam_pulang.
     */
    public function getTotalKerjaAttribute()
    {
        if ($this->jam_masuk && $this->jam_pulang) {
            $masuk = Carbon::parse($this->jam_masuk);
            $pulang = Carbon::parse($this->jam_pulang);

            // Jika jam pulang lebih kecil dari jam masuk (melewati tengah malam)
            if ($pulang->lt($masuk)) {
                $pulang->addDay();
            }

            $diff = $masuk->diff($pulang);
            
            // Format: 8j 30m (8 jam 30 menit)
            return $diff->format('%hj %im');
        }

        return '-';
    }

    /**
     * Scope untuk mempermudah filter berdasarkan tanggal tertentu.
     * Penggunaan: Absensi::today()->get();
     */
    public function scopeToday($query)
    {
        return $query->whereDate('tanggal', Carbon::today());
    }
}