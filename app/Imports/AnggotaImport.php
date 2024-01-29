<?php

namespace App\Imports;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class AnggotaImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $data = $collection->toArray();
        unset($data[0]);
        $insert = [];
        foreach ($data as $key => $item) {
            if (empty($item[0] || $item[2] || $item[3] || $item[4] || $item[5] || $item[6])) {
                continue;
            }
            try {
                DB::beginTransaction();
                $account = [
                    'name' => $item[1],
                    'email' => $item[5],
                    'password' => bcrypt($item[6]),
                ];
                $user = User::create($account);
                $insert = [
                    'eskul_id' => $item['0'],
                    'nama' => $item[1],
                    'kelas' => $item[2],
                    'jurusan' => $item[3],
                    'angkatan' => $item[4],
                    'user_id' => $user->id
                ];
                Anggota::create($insert);
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                dd($th);
                throw $th;
            }
        }
    }
}
