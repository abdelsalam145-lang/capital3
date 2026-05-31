<?php

use Illuminate\Support\Facades\Schedule;

// معالجة الفواتير المتأخرة وتوليد الفواتير الدورية يومياً
Schedule::command('invoices:process')->dailyAt('02:00');
