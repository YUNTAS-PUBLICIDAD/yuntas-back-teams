<?php

namespace App\Http\Controllers;

use App\Exports\blogsExport;
use App\Exports\productosExport;
use App\Exports\clientesExport;
use App\Exports\reclamosExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportProducto()
    {
        return Excel::download(new productosExport, 'productos.xlsx');
    }

    public function exportBlog()
    {
        return Excel::download(new blogsExport, 'blogs.xlsx');
    }

    public function exportCliente()
    {
        return Excel::download(new clientesExport, 'cliente.xlsx');
    }
    public function exportReclamo()
    {
        return Excel::download(new reclamosExport, 'reclamos.xlsx');
    }
}
