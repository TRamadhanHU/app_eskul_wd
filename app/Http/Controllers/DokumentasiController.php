<?php

namespace App\Http\Controllers;

use App\Models\Dokumentasi;
use App\Models\Eskul;
use App\Models\JadwalEskul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumentasiController extends Controller
{
    public function index(Request $request)
    {
        $validate = $request->validate([
            'eskul' => 'nullable|numeric',
            'bulan' => 'nullable|numeric',
        ]);

        $eskul = Eskul::all();
        $data = Dokumentasi::join('jadwal', 'jadwal.id', '=', 'dokumentasi.jadwal_id')
            ->join('eskul', 'eskul.id', '=', 'jadwal.eskul_id')
            ->select('dokumentasi.*', 'eskul.nama as eskul_nama', 'jadwal.desc as jadwal_desc')
            ->when($request->eskul, function ($query, $eskul) {
                return $query->where('eskul.id', $eskul);
            })
            ->when($request->bulan, function ($query, $bulan) {
                return $query->whereMonth('dokumentasi.tanggal', $bulan);
            })
            ->paginate(12);
        $jadwal = JadwalEskul::join('eskul', 'eskul.id', '=', 'jadwal.eskul_id')
            ->select('jadwal.*', 'eskul.nama as eskul_nama')
            ->get();

        return view('cms.master.dokumentasi.index', compact('data', 'eskul', 'jadwal'));
    }

    public function upsert(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required',
            'tanggal' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $file = $request->file('gambar');
            $nama_file = time() . "_" . $file->getClientOriginalName();
            Storage::putFileAs('public/dokumentasi', $file, $nama_file);
            
            Dokumentasi::create([
                'jadwal_id' => $request->jadwal_id,
                'tanggal' => $request->tanggal,
                'path' => $nama_file,
                'desc' => '-'
            ]);

            return redirect()->back()->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Data gagal disimpan');
        }
    }

    public function delete(Request $request)
    {
        try {
            $data = Dokumentasi::find($request->id);
            Storage::delete('public/dokumentasi/' . $data->path);
            $data->delete();
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data gagal dihapus');
        }
    }
}
