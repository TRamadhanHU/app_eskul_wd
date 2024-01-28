<?php

namespace App\Http\Controllers;

use App\Imports\AnggotaImport;
use App\Models\Anggota;
use App\Models\Eskul;
use App\Models\JadwalEskul;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'eskul' => 'nullable|numeric',
            'kelas' => 'nullable|numeric'
        ]);

        $eskulId = $request->eskul ?? null;
        $kelas = $request->kelas ?? null;

        $data = Anggota::select('id', 'user_id', 'eskul_id', 'nama', 'kelas', 'jurusan', 'angkatan')
            ->when($eskulId, function ($query, $eskulId) {
                return $query->where('eskul_id', $eskulId);
            })
            ->when($kelas, function ($query, $kelas) {
                return $query->where('kelas', $kelas);
            })
            ->get()
            ->toArray();
        $listKelas = Anggota::select('kelas')->pluck('kelas')->toArray();
        $listEskul = Eskul::select('id','nama')->get()->toArray();

        return view('cms.anggota', compact('data', 'listKelas', 'listEskul')); // Mengasumsikan view untuk daftar anggota
    }

    public function show($id)
    {
        $data = Anggota::find($id);
        return response()->json($data);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        $import = Excel::import(new AnggotaImport, request()->file('file'));
    }

}
