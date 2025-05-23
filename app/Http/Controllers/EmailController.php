<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CorreoPersonalizado;
use Exception;

class EmailController extends Controller
{
    public function getMail()
    {
       $data= ['name' => 'Jefferson Peña'];
       Mail::to('jefferson152530@gmail.com')->send(new CorreoPersonalizado($data));
    }
}
