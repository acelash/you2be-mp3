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
        Commands\GetYTbyChannel::class,
        Commands\GetAudioFromYoutube::class,
        Commands\OptimizeImages::class,
        Commands\MoveFiles::class,
        Commands\RemoveSkippedFiles::class,
        Commands\RemoveFiles::class,
    ];

    protected $outputFile = 'app/schedules.txt';

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('getmovies 0')->cron("0 */3 * * *")
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


        // BY CHANNEL
        $schedule->command('getbychannel "UC2pmfLm7iq6Ov1UwYrWYkZA"')->dailyAt('03:20') // VEVO
            ->sendOutputTo(storage_path($this->outputFile))
            ->after(function () {
                $this->saveLogs('GetYTbyChannel');
            });
        $schedule->command('getbychannel "UCCTR5nIFvWBW9MeGEGPNG4g"')->dailyAt('04:30') // CAT Music
            ->sendOutputTo(storage_path($this->outputFile))
            ->after(function () {
                $this->saveLogs('GetYTbyChannel');
            });
        $schedule->command('getbychannel "UCFpD-o5sN0cSk8pgZSGLJ4g"')->dailyAt('05:30') // BlackStarTV
            ->sendOutputTo(storage_path($this->outputFile))
            ->after(function () {
                $this->saveLogs('GetYTbyChannel');
            });
        // END BY CHANNEL




        $schedule->command('removeold')->hourly()
            ->sendOutputTo(storage_path($this->outputFile))
            ->after(function () {
                $this->saveLogs('RemoveFiles');
            });

        $schedule->command('removemp3')->cron("0 */3 * * *")
            ->sendOutputTo(storage_path($this->outputFile))
            ->after(function () {
                $this->saveLogs('RemoveSkippedFiles');
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
