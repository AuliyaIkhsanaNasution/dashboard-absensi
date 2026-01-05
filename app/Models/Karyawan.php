<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    // Tambahkan baris ini agar Laravel mengizinkan penyimpanan data
    protected $fillable = [
        'nip', 
        'nama', 
        'jabatan', 
        'penempatan', 
        'no_wa', 
        'status'
    ];
}