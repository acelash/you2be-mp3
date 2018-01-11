<?php

namespace App\Console\Commands;


use App\Models\Movie;
use Illuminate\Console\Command;

class RemoveFiles extends Command
{

    protected $signature = 'removeold';

    protected $description = '...';
    protected $ttl = 3600*6;

    public function handle()
    {
        //ini_set('display_errors', 1);
        //echo "starting...\n";
        $startTime = time();
        $path = "public/audio/";

        $files = glob (base_path($path."*.mp3"));
        //if file exists, save
        if(count($files)){
            echo " files: ".count($files).".  \n";
            foreach ($files as $filename){
                if (file_exists($filename)) {
                    $stats = stat($filename);
                    if(time() - $this->ttl > $stats['atime']){
                        unlink($filename);
                    }
                }
            }
        } else {
            echo "no files.  \n";
        }

        $endTime = time();
        echo "end (".($endTime - $startTime)." sec). \n";
    }
}
