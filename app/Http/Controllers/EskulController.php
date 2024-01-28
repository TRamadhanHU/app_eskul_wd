<?php

namespace App\Http\Controllers;

use App\Models\Eskul;
use App\Models\JadwalEskul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EskulController extends Controller
{
    public function index(Request $request)
    {
        $authRole = auth()->user()->role_id;
        $data = Eskul::select('id', 'nama', 'desc')
            ->get()
            ->toArray();

        return view('cms.master.eskul.index', compact('data'));
    }

    public function upsert(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'desc' => 'required',
        ]);

        $data = [
            'nama' => $request->name,
            'desc' => $request->desc,
        ];
        try {
            DB::beginTransaction();
            if ($request->has('id')) {
                Eskul::find($request->id)->update($data);
            } else {
                Eskul::create($data);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan');
        }

        return redirect()->route('eskul')->with('success', 'Data berhasil disimpan');
    }

    public function show($id)
    {
        $data = Eskul::find($id);
        return response()->json($data);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $jadwal = JadwalEskul::where('eskul_id', $id)->first();
            if ($jadwal) {
                return redirect()->back()->with('error', 'Data gagal dihapus, karena data masih digunakan');
            }
            Eskul::find($id)->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Data gagal dihapus');
        }

        return redirect()->route('eskul')->with('success', 'Data berhasil dihapus');
    }
}
