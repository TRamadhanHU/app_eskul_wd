<?php

namespace App\Exports;

use App\Models\Anggota;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AnggotaExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    // constructor

    protected $filter;

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function collection()
    {
        $kelas = $this->filter['kelas'];
        $angkatan = $this->filter['angkatan'];
        $eskul = $this->filter['eskul'];
        $anggota = Anggota::join('eskul', 'anggota.eskul_id', '=', 'eskul.id')
            ->select('eskul.nama as eskul', 'anggota.nama', 'anggota.kelas', 'anggota.jurusan', 'anggota.angkatan')
            ->when($kelas, function ($query, $kelas) {
                return $query->where('anggota.kelas', $kelas);
            })
            ->when($angkatan, function ($query, $angkatan) {
                return $query->where('anggota.angkatan', $angkatan);
            })
            ->when($eskul, function ($query, $eskul) {
                return $query->where('eskul.id', $eskul);
            })
            ->orderBy('eskul.nama', 'asc')
            ->orderBy('anggota.kelas', 'asc')
            ->get();

        // mapping agar sesuai heading eskul, nama, kelas, jurusan, angkatan
        $anggota->map(function ($item) {
            return [
                'eskul' => $item->eskul,
                'nama' => $item->nama,
                'kelas' => $item->kelas,
                'jurusan' => $item->jurusan,
                'angkatan' => $item->angkatan,
            ];
        });

        return $anggota;
    }

    public function headings(): array
    {
        return [
            'eskul',
            'Nama',
            'Kelas',
            'Jurusan',
            'Angkatan',
        ];
    }
}
