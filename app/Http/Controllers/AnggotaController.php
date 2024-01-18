<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $data = Anggota::select('id', 'user_id', 'eskul_id', 'nama', 'kelas', 'jurusan', 'angkatan')
            ->get()
            ->toArray();

        return view('cms.anggota', compact('data')); // Mengasumsikan view untuk daftar anggota
    }

    public function show($id)
    {
        $data = Anggota::find($id);
        return response()->json($data);
    }

}
