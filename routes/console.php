<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule email sending command to run every minute
Schedule::command('emails:send-pending')->everyMinute();

// Schedule notification emails to run every 2 minutes (sends top 20)
Schedule::command('notifications:send-emails')->everyTwoMinutes();
