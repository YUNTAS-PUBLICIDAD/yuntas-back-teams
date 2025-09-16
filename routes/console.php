<?php

use App\Console\Commands\EnviarPrimeraPublicidad;
use Illuminate\Support\Facades\Schedule;

Schedule::command(EnviarPrimeraPublicidad::class)->timezone('America/Lima')->cron('*/1 * * * *');
