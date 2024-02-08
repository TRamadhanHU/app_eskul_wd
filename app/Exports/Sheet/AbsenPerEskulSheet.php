<?php

namespace App\Exports\Sheet;

use App\Models\Absensi;
use App\Models\Anggota;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class AbsenPerEskulSheet implements FromView, WithTitle, ShouldAutoSize
{
    protected $eskul;
    protected $filter;
    public function __construct($eskul, $filter)
    {
        $this->eskul = $eskul;
        $this->filter = $filter;
    }

    public function title(): string
    {
        return $this->eskul->nama;
    }

    public function view(): \Illuminate\Contracts\View\View
    {
        $filter = (object) $this->filter;

        $tahun = $filter->tahun ?? date('Y');
        $bulan = $filter->bulan ?? date('m');
        $kelas = $filter->kelas ?? null;
        $eskulId = $this->eskul->id;

        $listFormatted = [];
        $listAbsen = Absensi::whereYear('waktu', $tahun)->whereMonth('waktu', $bulan)
                    ->where('eskul_id', $eskulId)->get()
                    ->groupBy('eskul_id')
                    ->toArray();

        foreach ($listAbsen as $eskulId => $absenList) {
            foreach ($absenList as $absen) {
                $anggotaId = $absen['anggota_id'];
                $tanggal = \Carbon\Carbon::parse($absen['waktu'])->format('d');

                if (!isset($listFormatted[$eskulId][$anggotaId][$tanggal])) {
                    $listFormatted[$eskulId][$anggotaId][$tanggal] = 0;
                }
                $listFormatted[$eskulId][$anggotaId][$tanggal]++;
            }
        }

        $listTanggal = Absensi::whereYear('waktu', $tahun)->whereMonth('waktu', $bulan)->where('eskul_id', $eskulId)->get()->groupBy('eskul_id')->map(function ($item) {
            return $item->pluck('waktu')->map(function ($item) {
                return date('d', strtotime($item));
            })->unique();
        })->toArray();

        $namaSiswa = Anggota::when($kelas, function ($query, $kelas) {
            return $query->where('kelas', $kelas);
        })->select('nama', 'id', 'eskul_id', 'kelas')
        ->orderBy('kelas', 'asc')->orderBy('nama', 'asc')
        ->get()->groupBy('eskul_id')->toArray();

        $listAbsen = $listFormatted;

        return view('cms.export-absen', compact('listAbsen', 'listTanggal', 'namaSiswa', 'eskulId'));
    }

}
