<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Anggota;
use App\Models\Eskul;
use Illuminate\Http\Request;

class ListAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $bulan = $request->bulan ?? date('m');
        $kelas = $request->kelas ?? null;

        
        $eskuls = Eskul::all();
        $listFormatted = [];
        $listAbsen = Absensi::whereYear('waktu', $tahun)->whereMonth('waktu', $bulan)->get()->groupBy('eskul_id')->toArray();

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

        $listTanggal = Absensi::whereYear('waktu', $tahun)->whereMonth('waktu', $bulan)->get()->groupBy('eskul_id')->map(function ($item) {
            return $item->pluck('waktu')->map(function ($item) {
                return date('d', strtotime($item));
            })->unique();
        })->toArray();

        $namaSiswa = Anggota::when($kelas, function ($query, $kelas) {
            return $query->where('kelas', $kelas);
        })->select('nama', 'id', 'eskul_id', 'kelas')->get()->groupBy('eskul_id')->toArray();

        $listAbsen = $listFormatted;

        return view('cms.listabsensi', compact('eskuls', 'listAbsen', 'listTanggal', 'namaSiswa'));
    }
}
