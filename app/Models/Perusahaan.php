<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     * Laravel secara otomatis akan menganggap nama tabelnya 'perusahaans',
     * namun mendefinisikannya secara eksplisit adalah praktik yang baik.
     */
    protected $table = 'perusahaans';

    /**
     * Field yang dapat diisi secara massal (Mass Assignment).
     */
    protected $fillable = [
        'nama_pt', 
        'email', 
        'telepon', 
        'alamat', 
        'logo',
        'latitude',
        'longitude',
    ];

    /**
     * Relasi ke Model Karyawan.
     * Satu perusahaan memiliki banyak karyawan (One-to-Many).
     */
    public function karyawans()
    {
        return $this->hasMany(Karyawan::class, 'perusahaan_id');
    }

    /**
     * Relasi ke Model Absensi.
     * Satu perusahaan memiliki banyak catatan absensi historis (One-to-Many).
     */
    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'perusahaan_id');
    }
}