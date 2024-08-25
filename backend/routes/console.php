<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:check-user-tasks')->everyMinute();
