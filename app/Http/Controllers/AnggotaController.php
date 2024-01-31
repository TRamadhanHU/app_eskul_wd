<?php

namespace App\Http\Controllers;

use App\Imports\AnggotaImport;
use App\Models\Anggota;
use App\Models\Eskul;
use App\Models\JadwalEskul;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'eskul' => 'nullable|numeric',
            'kelas' => 'nullable|numeric',
            'search' => 'nullable|string',
        ]);

        $eskulId = $request->eskul ?? null;
        $kelas = $request->kelas ?? null;
        $search = $request->search ?? null;

        $data = Anggota::select('id', 'user_id', 'eskul_id', 'nama', 'kelas', 'jurusan', 'angkatan')
            ->when($eskulId, function ($query, $eskulId) {
                return $query->where('eskul_id', $eskulId);
            })
            ->when($kelas, function ($query, $kelas) {
                return $query->where('kelas', $kelas);
            })
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%$search%");
            })
            ->get()
            ->toArray();
        $listKelas = Anggota::select('kelas')->pluck('kelas')->toArray();
        $listEskul = Eskul::select('id','nama')->get()->toArray();

        return view('cms.anggota', compact('data', 'listKelas', 'listEskul')); // Mengasumsikan view untuk daftar anggota
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);
        try {
            DB::beginTransaction();
            $import = Excel::import(new AnggotaImport, request()->file('file'));
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Data gagal diimport ' . $th->getMessage());
            throw $th;
        }

        return redirect()->back()->with('success', 'Data berhasil diimport');
    }

    public function templateImport()
    {
        $template = public_path('template/import-anggota.xlsx');
        return response()->download($template);
    }

    public function show($id)
    {
        $data = Anggota::join('users', 'users.id', '=', 'anggota.user_id')
            ->select('anggota.id', 'anggota.user_id', 'anggota.eskul_id', 'anggota.nama', 'anggota.kelas', 'anggota.jurusan', 'anggota.angkatan', 'users.email')
            ->where('anggota.id', $id)
            ->first();
        return response()->json($data);
    }

    public function upsert(Request $request)
    {
        $request->validate([
            'id' => 'nullable|numeric',
            'eskul_id' => 'required|numeric',
            'nama' => 'required|string',
            'kelas' => 'required|string',
            'jurusan' => 'required|string',
            'angkatan' => 'required|numeric|digits:4',
            'password' => 'nullable|required_if:id,null|string',
        ]);

        if ($request->id) {
            $anggota = Anggota::find($request->id);
            $request->validate([
                'email' => 'required|string|email|unique:users,email,' . $anggota->user_id,
            ]);
        } else {
            $request->validate([
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string',
            ]);
        }

        try {
            DB::beginTransaction();
            if ($request->id) {
                $anggota = Anggota::find($request->id);
                $anggota->eskul_id = $request->eskul_id;
                $anggota->nama = $request->nama;
                $anggota->kelas = $request->kelas;
                $anggota->jurusan = $request->jurusan;
                $anggota->angkatan = $request->angkatan;
                $anggota->save();

                $user = User::find($anggota->user_id);
                $user->email = $request->email;
                if ($request->password) {
                    $user->password = bcrypt($request->password);
                }
                $user->save();
            } else {
                $user = User::create([
                    'name' => $request->nama,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                ]);

                $anggota = Anggota::create([
                    'eskul_id' => $request->eskul_id,
                    'nama' => $request->nama,
                    'kelas' => $request->kelas,
                    'jurusan' => $request->jurusan,
                    'angkatan' => $request->angkatan,
                    'user_id' => $user->id,
                ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Data gagal disimpan' . $th->getMessage());
            throw $th;
        }

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $anggota = Anggota::find($id);
            $anggota->delete();

            $user = User::find($anggota->user_id);
            $user->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Data gagal dihapus');
            throw $th;
        }

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

}
