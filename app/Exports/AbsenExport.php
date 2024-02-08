<?php

namespace App\Exports;

use App\Exports\Sheet\AbsenPerEskulSheet;
use App\Models\Eskul;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AbsenExport implements WithMultipleSheets
{
    protected $filter;
    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function sheets(): array
    {
        $listEskul = Eskul::select('id','nama')->get();
        $sheets = [];
        foreach ($listEskul as $eskul) {
            $sheets[] = new AbsenPerEskulSheet($eskul, $this->filter);
        }
        return $sheets;
    }
}
