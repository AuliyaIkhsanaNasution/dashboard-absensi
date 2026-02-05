<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    use HasFactory;

    protected $table = 'perusahaans';

    protected $fillable = [
        'nama_pt', 
        'email', 
        'telepon', 
        'alamat', 
        'logo',
        'latitude',
        'longitude',
        'radius_absen',
    ];

    public function karyawans()
    {
        return $this->hasMany(Karyawan::class, 'perusahaan_id');
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'perusahaan_id');
    }
}
