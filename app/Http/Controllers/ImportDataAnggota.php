<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ImportDataAnggota extends Controller
{
    /**
     * Import data anggota dari Excel.
     *
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        // Tampilkan modal
        return view('import-anggota');
    }

    /**
     * Proses import data anggota dari Excel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        // Cek apakah file Excel ada
        if (!Storage::exists($request->file('file')->store('app/anggota'))) {
            return redirect()->back()->withErrors('File Excel tidak ditemukan.');
        }

        // Load file Excel
        $data = Excel::load(storage_path('app/anggota.xlsx'))->get();

        // Buat array data anggota
        $anggotas = [];
        foreach ($data as $row) {
            $anggotas[] = [
                'nama' => $row['nama'],
                'kelas' => $row['kelas'],
                'jurusan' => $row['jurusan'],
                'angkatan' => $row['angkatan'],
            ];
        }

        // Simpan data anggota ke database
        foreach ($anggotas as $anggota) {
            // Buat model Anggota
            $anggotaModel = new \App\Models\Anggota();

            // Set data anggota
            $anggotaModel->nama = $anggota['nama'];
            $anggotaModel->kelas = $anggota['kelas'];
            $anggotaModel->jurusan = $anggota['jurusan'];
            $anggotaModel->angkatan = $anggota['angkatan'];

            // Simpan data anggota
            $anggotaModel->save();
        }

        // Kembali ke halaman anggota
        return redirect()->route('anggota.index')->withSuccess('Data anggota berhasil diimpor.');
    }
}
