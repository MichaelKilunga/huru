<?php

use Illuminate\Support\Facades\Schedule;

// Drain the database queue every minute on hosts without a long-running worker.
Schedule::command('queue:work --stop-when-empty --tries=2 --max-time=50')
    ->everyMinute()
    ->withoutOverlapping();

// Keep the local legal aid directory fresh.
Schedule::command('huru:import-legal-aid')->weeklyOn(1, '03:00');
