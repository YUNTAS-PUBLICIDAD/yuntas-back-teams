<?php

use App\Console\Commands\SendSecondPromotionalEmail;
use App\Console\Commands\SendThirdPromotionalEmail;
use Illuminate\Support\Facades\Schedule;

Schedule::command(SendSecondPromotionalEmail::class)->timezone('America/Lima')->cron('*/1 * * * *');

Schedule::command(SendThirdPromotionalEmail::class)->timezone('America/Lima')->cron('*/1 * * * *');
