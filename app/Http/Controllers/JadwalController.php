<?php

namespace App\Http\Controllers;

use App\Models\JadwalEskul;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {   
        $data = JadwalEskul::all();

        return view('cms.jadwal',compact('data')) ;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'eskul_id' => 'required',
            'hari' => 'required',
            'kegiatan' => 'required',
            'jam' => 'required',
        ]);

        $jadwal_eskul = new JadwalEskul();
        $jadwal_eskul->eskul_id = $request->eskul_id;
        $jadwal_eskul->hari = $request->hari;
        $jadwal_eskul->desc = $request->kegiatan;
        $jadwal_eskul->jam = $request->jam;

        $jadwal_eskul->save();

        return redirect()->route('jadwal')->with('success', 'Data jadwal eskul berhasil disimpan');
    }

}
