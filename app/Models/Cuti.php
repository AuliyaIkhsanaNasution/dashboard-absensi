<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    use HasFactory;

    // Pastikan nama tabel benar (biasanya Laravel menganggap 'cutis')
    protected $table = 'cutis'; 

    protected $fillable = [
        'karyawan_id',
        'jenis_cuti',
        'tanggal_mulai',        
        'tanggal_selesai',      
        'tanggal_masuk_kerja',  
        'keterangan',
        'dokumen'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_masuk_kerja' => 'date',
    ];

    // Relasi ke Karyawan
    public function karyawan()
    {
        // Menambahkan foreign key secara eksplisit agar lebih aman
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    // Helper untuk menghitung durasi cuti
    public function getDurasiAttribute()
    {
        // PERBAIKAN: Nama kolom harus 'tanggal_mulai', bukan 'tgl_mulai'
        if (!$this->tanggal_mulai || !$this->tanggal_selesai) return 0;

        // Karena sudah masuk $casts sebagai 'date', 
        // variabel ini sudah otomatis menjadi objek Carbon.
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai) + 1;
    }
}