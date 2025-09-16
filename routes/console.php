<?php

use App\Console\Commands\SendFirstPromotionalEmail;
use Illuminate\Support\Facades\Schedule;

Schedule::command(SendFirstPromotionalEmail::class)->timezone('America/Lima')->cron('*/1 * * * *');
