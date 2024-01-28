<?php

namespace App\Imports;

use Illuminate\Support\Collection;
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
            $insert[$key] = [
                'eskul_id' => $item['0'],
                'nama' => $item[1],
                'kelas' => $item[2],
                'jurusan' => $item[3],
                'angkatan' => $item[4],
                'email' => $item[5],
                'password' => $item[6],
            ];
        }
    }
}
