<?php

namespace App\Http\Controllers;

use App\Models\Eskul;
use App\Models\JadwalEskul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\type;

class JadwalController extends Controller
{
    public function index()
    {   
        $data = JadwalEskul::join('eskul','eskul.id','=','jadwal.eskul_id')
            ->select('jadwal.*','eskul.nama')
            ->orderBy('hari','asc')
            ->get()->groupBy('eskul_id')->toArray();
        $eskuls = Eskul::all();

        $hari = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];
        return view('cms.jadwal',compact('data','eskuls','hari'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'eskul_id' => 'required',
            'hari' => 'required',
            'desc' => 'required',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $jadwal_eskul = new JadwalEskul();
            $jadwal_eskul->eskul_id = $request->eskul_id;
            $jadwal_eskul->hari = $request->hari;
            $jadwal_eskul->desc = $request->desc;
            $jadwal_eskul->waktu_mulai = $request->waktu_mulai;
            $jadwal_eskul->waktu_selesai = $request->waktu_selesai;

            $jadwal_eskul->save();
            DB::commit();
            return redirect()->route('jadwal')->with('success', 'Data jadwal eskul berhasil disimpan');
        } catch (\Throwable $th) {
            return redirect()->route('jadwal')->with('error', 'Data jadwal eskul gagal disimpan');
        }
    }

    public function show($id)
    {
        $data = JadwalEskul::find($id);
        $data->waktu_mulai = date('H:i',strtotime($data->waktu_mulai));
        $data->waktu_selesai = date('H:i',strtotime($data->waktu_selesai));
        return response()->json($data);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'eskul_id' => 'required',
            'hari' => 'required',
            'desc' => 'required',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $jadwal_eskul = JadwalEskul::find($request->id);
            $jadwal_eskul->eskul_id = $request->eskul_id;
            $jadwal_eskul->hari = $request->hari;
            $jadwal_eskul->desc = $request->desc;
            $jadwal_eskul->waktu_mulai = $request->waktu_mulai;
            $jadwal_eskul->waktu_selesai = $request->waktu_selesai;

            $jadwal_eskul->save();
            DB::commit();
            return redirect()->route('jadwal')->with('success', 'Data jadwal eskul berhasil disimpan');
        } catch (\Throwable $th) {
            return redirect()->route('jadwal')->with('error', 'Data jadwal eskul gagal disimpan');
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $jadwal_eskul = JadwalEskul::find($id);
            $jadwal_eskul->delete();
            DB::commit();
            return redirect()->route('jadwal')->with('success', 'Data jadwal eskul berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->route('jadwal')->with('error', 'Data jadwal eskul gagal dihapus');
        }
    }

}
