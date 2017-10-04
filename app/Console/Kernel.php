<?php

namespace App\Console;

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
        Commands\RemoveDrafts::class,
        Commands\GetNewYoutubeMovies::class,
        Commands\GetAudioFromYoutube::class,
        Commands\OptimizeImages::class,
    ];

    protected $outputFile = 'app/schedules.txt';

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('RemoveDrafts')->dailyAt('05:30')
            ->sendOutputTo(storage_path($this->outputFile))
            ->after(function () {
                $this->saveLogs('RemoveDrafts');
            });
        $schedule->command('GetNewYoutubeMovies')->daily()
            ->sendOutputTo(storage_path($this->outputFile))
            ->after(function () {
                $this->saveLogs('GetNewYoutubeMovies');
            });
        $schedule->command('GetAudioFromYoutube')->hourly()
            ->sendOutputTo(storage_path($this->outputFile))
            ->after(function () {
                $this->saveLogs('GetAudioFromYoutube');
            });
        $schedule->command('OptimizeImages')->daily()
            ->sendOutputTo(storage_path($this->outputFile))
            ->after(function () {
                $this->saveLogs('OptimizeImages');
            });
    }

    protected function saveLogs($scheduleName)
    {
       /* $output = File::get(storage_path($this->outputFile));
        $logModel = (new ScheduleLog())->newInstance();
        $logData = [
            'schedule' => $scheduleName,
            'schedule_output' => trim($output)
        ];
        if ($logModel->validate($logData)) {
            $logModel->fill($logData);
            $logModel->save();
        } else
            echo 'invalid data for logs';*/
    }

    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
