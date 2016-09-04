<?php

namespace App\Console;

use Slack;
use Carbon\Carbon;
use Log;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // reminder (10:30)
        $schedule->call(function () {
            Slack::send('[大阪] 今日お米たべたい人〜？ :rice: http://tinyurl.com/tamrice (11:40 までに書いてね)');
        })->weekdays()->when(function() {
            return $this->checkCronTime(10, 30);
        });

        // pickup (11:45)
        $schedule->call(function () {
            file_get_contents('https://tamrice.herokuapp.com/pickup_cron');
        })->weekdays()->when(function() {
            return $this->checkCronTime(11, 45);
        });
    }

    protected function checkCronTime($h, $m)
    {
        $cron_span = 10;

        $cron_time = Carbon::now()->setTime($h, $m);
        $diff = $cron_time->diffInMinutes(Carbon::now(), false);

        return ($diff >= 0 && $diff < $cron_span);
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
