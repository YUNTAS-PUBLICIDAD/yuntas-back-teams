<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappGeneral extends Model
{
    protected $table = 'whatsapp_general';

    protected $fillable = [
        'caption',
        'image',
        'current_page',
    ];
}
