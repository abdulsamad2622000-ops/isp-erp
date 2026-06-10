<?php
use Illuminate\Support\Facades\Schedule;

Schedule::command('invoices:generate-monthly')->dailyAt('00:01');
Schedule::command('notifications:auto-send')->dailyAt('09:00');