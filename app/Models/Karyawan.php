<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Karyawan extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'karyawans';

    protected $fillable = [
        'perusahaan_id',
        'foto',
        'nip',
        'nama',
        'jabatan',
        'alamat',
        'tanggal_lahir',
        'no_wa',
        'status',
        'password', 
    ];

    protected $hidden = [
        'password', 
    ];

    // RELASI (TIDAK BERUBAH)
    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'karyawan_id');
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }

    public function totalHadirBulanIni()
    {
        return $this->absensis()
            ->where('status', 'Hadir')
            ->whereMonth('tanggal', date('m'))
            
            ->whereYear('tanggal', date('Y'))
            ->count();
    }

public function getFotoAttribute($value)
{
    return $value
        ? asset('storage/' . $value)
        : null;
}
}