<?php

namespace App\Console;

use App\Mail\CapitalWeekRemind;
use App\Mail\DebtMonthRemind;
use App\Services\CapitalStatisticService;
use App\Services\DebtStatisticService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 备份数据库
//        $schedule->command('backup:run --only-db')->dailyAt('01:00');

        // 每日重置数据库
        $schedule->command('migrate:refresh --seed --force')->dailyAt('01:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
