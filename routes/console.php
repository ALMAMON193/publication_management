<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('user:donation')->hourly();
Schedule::command('payment')->everyFifteenMinutes();
Schedule::command('user:membership')->hourly();
