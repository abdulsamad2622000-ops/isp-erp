<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('invoices:generate-monthly')->dailyAt('00:01');