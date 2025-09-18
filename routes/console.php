<?php

use App\Console\Commands\SendFirstPromotionalEmail;
use App\Console\Commands\SendSecondPromotionalEmail;
use Illuminate\Support\Facades\Schedule;

Schedule::command(SendFirstPromotionalEmail::class)->timezone('America/Lima')->cron('*/1 * * * *');

Schedule::command(SendSecondPromotionalEmail::class)->timezone('America/Lima')->cron('*/1 * * * *');
