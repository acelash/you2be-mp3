<?php

namespace App\Console;

use App\Models\Admin\TaskLog;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\File;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\GetNewYoutubeMovies::class,
        Commands\GetYTbyChannel::class,
        Commands\OptimizeImages::class,
        Commands\RemoveFiles::class,
    ];

    protected $outputFile = 'app/schedules.txt';

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('getbychannel "0"')->everyTenMinutes()
            ->sendOutputTo(storage_path($this->outputFile))
            ->after(function () {
                $this->saveLogs('GetYTbyChannel');
            });

        $schedule->command('removeold')->hourly()
            ->sendOutputTo(storage_path($this->outputFile))
            ->after(function () {
                $this->saveLogs('RemoveFiles');
            });


       /* $schedule->command('optimage')->daily()
            ->sendOutputTo(storage_path($this->outputFile))
            ->after(function () {
                $this->saveLogs('OptimizeImages');
            });*/
    }

    protected function saveLogs($scheduleName)
    {
        $output = File::get(storage_path($this->outputFile));
        $logModel = (new TaskLog())->newInstance();
        $logData = [
            'job' => $scheduleName,
            'output' => trim($output),
            'created_at' => time()
        ];
        $logModel->fill($logData);
        $logModel->save();
    }

    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
