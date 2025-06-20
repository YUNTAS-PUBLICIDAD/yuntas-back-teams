<?php

namespace App\Exports;

use App\Models\Reclamo;
use Maatwebsite\Excel\Concerns\FromCollection;

class reclamosExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Reclamo::all();
    }
}
