<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:send-lease-reminders')->dailyAt('09:00');