<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Anggota;
use App\Models\JadwalEskul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiswaAbsenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        abort_if(!auth()->user()->role_id == 4, 403, 'Akses tidak diizinkan.');
        $user = auth()->user();
        $anggota = Anggota::join('eskul', 'eskul.id', '=', 'anggota.eskul_id')
            ->where('user_id', $user->id)
            ->select('anggota.*', 'eskul.nama as eskul')
            ->first();
        if (!$anggota) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Akun anda tidak terdaftar sebagai anggota.');
        }

        $data = [
            'nama' => auth()->user()->name,
            'anggota' => $anggota,
            'absen' => Absensi::leftJoin('anggota', 'anggota.id', '=', 'absensi.anggota_id')
                ->leftJoin('eskul', 'eskul.id', '=', 'absensi.eskul_id')
                ->leftJoin('jadwal', 'jadwal.id', '=', 'absensi.jadwal_id')
                ->where('anggota_id', $anggota->id)
                ->select('absensi.*', 'eskul.nama as eskul', 'jadwal.desc')
                ->orderBy('absensi.created_at', 'desc')
                ->paginate(10),
            ];
            
        $idAbsenToday = JadwalEskul::join('absensi', 'absensi.jadwal_id', '=', 'jadwal.id')
            ->where('anggota_id', $anggota->id)
            ->whereDate('absensi.created_at', date('Y-m-d'))
            ->select('jadwal.id as jadwal_id')
            ->pluck('jadwal_id')->toArray();

        $listJadwalHariIni = JadwalEskul::join('eskul', 'eskul.id', '=', 'jadwal.eskul_id')
            ->where('eskul_id', $anggota->eskul_id)
            ->where('hari', date('N'))
            ->whereNotIn('jadwal.id', $idAbsenToday)
            ->select('jadwal.*', 'eskul.nama as eskul')
            ->get();

        return view('anggotaUi.index', compact('data', 'listJadwalHariIni'));
    }

    public function absen($idJadwal, $idAnggota)
    {
        $jadwal = JadwalEskul::findOrfail($idJadwal);

        $keterangan = 'hadir';
        if (date('H:i:s') > $jadwal->waktu_selesai) {
            $keterangan = 'terlambat';
        }
        try {
            DB::beginTransaction();
            $absensi = Absensi::create([
                'anggota_id' => $idAnggota,
                'eskul_id' => $jadwal->eskul_id,
                'jadwal_id' => $idJadwal,
                'waktu' => date('Y-m-d H:i:s'),
                'keterangan' => $keterangan,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            return redirect()->route('siswa.home')->with('error', 'Absen gagal.');
        }

        return redirect()->route('siswa.home')->with('success', 'Absen berhasil.');
    }
}
