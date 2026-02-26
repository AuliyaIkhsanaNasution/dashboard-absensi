<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RekapKehadiranExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data; // data array dari controller
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'NIP',
            'Nama',
            'Jabatan',
            'Perusahaan',
            'Tepat Waktu',
            'Terlambat',
            'Izin',
            'Tidak Hadir',
            'Persentase Kehadiran (%)',
        ];
    }

    public function map($row): array
    {
        return [
            $row['nip'],
            $row['nama'],
            $row['jabatan'],
            $row['perusahaan'],
            $row['tepat_waktu'],
            $row['terlambat'],
            $row['izin'],
            $row['tidak_hadir'],
            $row['persentase_kehadiran'],
        ];
    }
}