<?php

namespace App\Exports;

use App\Models\Blog;
use Maatwebsite\Excel\Concerns\FromCollection;

class blogsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Blog::all();
    }
}
