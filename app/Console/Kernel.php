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
        Commands\RemoveDrafts::class,
        Commands\GetNewYoutubeMovies::class,
        Commands\GetAudioFromYoutube::class,
        Commands\OptimizeImages::class,
        Commands\MoveFiles::class,
    ];

    protected $outputFile = 'app/schedules.txt';

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('getmovies 0')->dailyAt('01:20')//->cron("*/15 * * * *")
            ->sendOutputTo(storage_path($this->outputFile))
            ->after(function () {
                $this->saveLogs('GetNewYoutubeMovies');
            });

        $schedule->command('getmovies "Official Audio"')->dailyAt('23:20')
        ->sendOutputTo(storage_path($this->outputFile))
            ->after(function () {
                $this->saveLogs('GetNewYoutubeMovies');
            });
        $schedule->command('getmovies "Official Video"')->dailyAt('22:20')
            ->sendOutputTo(storage_path($this->outputFile))
            ->after(function () {
                $this->saveLogs('GetNewYoutubeMovies');
            });
        $schedule->command('getmovies "Клип"')->dailyAt('21:20')
            ->sendOutputTo(storage_path($this->outputFile))
            ->after(function () {
                $this->saveLogs('GetNewYoutubeMovies');
            });




        $schedule->command('movemp3')->dailyAt('02:20')
            ->sendOutputTo(storage_path($this->outputFile))
            ->after(function () {
                $this->saveLogs('MoveFiles');
            });
        $schedule->command('getmp3')->dailyAt('03:20')
            ->sendOutputTo(storage_path($this->outputFile))
            ->after(function () {
                $this->saveLogs('GetAudioFromYoutube');
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
