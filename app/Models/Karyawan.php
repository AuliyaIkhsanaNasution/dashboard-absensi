<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
class Karyawan extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'karyawans';

    protected $fillable = [
        'perusahaan_id',
        'foto',
        'nip',
        'nama',
        'email',
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

public function setPasswordAttribute($value)
{
    // Jika nilai sudah di-hash (ditandai dengan $2y$), jangan hash ulang
    if (substr($value, 0, 4) === '$2y$') {
        $this->attributes['password'] = $value;
    } else {
        $this->attributes['password'] = Hash::make($value);
    }
}

}