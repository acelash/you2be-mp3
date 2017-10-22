<?php

namespace App\Console\Commands;


use App\Models\Movie;
use App\Models\Song;
use Illuminate\Console\Command;

class GetAudioFromYoutube extends Command
{

    protected $signature = 'getmp3';

    protected $description = '...';

    public function handle()
    {
        echo "starting...\n";
        $startTime = time();
        $path = "public/audio/";

        $songs = (new Song())->getAll(true)
            ->where("songs.state_id",config("constants.STATE_DRAFT"))
           // ->where("songs.state_id",config("constants.STATE_SKIPPED")) // temporar
            ->take(20)
            ->get();
        echo "songs found:".$songs->count()." \n";
        foreach ($songs as $song){
            echo "proccessing: ".$song->id." \n";
            $filename  = $path.$song->id.".%(ext)s";
           // $command = 'youtube-dl -o "/home/admin/web/mp3.cardeon.ru/public_html/'.$filename.'"  -f "bestaudio"  https://www.youtube.com/watch?v='.$song->source_id;
            $command = '/usr/local/bin/youtube-dl -o "/home/admin/web/mp3cloud.su/public_html/'.$filename.'"  --extract-audio --audio-format mp3 --audio-quality 160K https://www.youtube.com/watch?v='.$song->source_id;
            //echo "command: ".$command." \n";
            $output = shell_exec($command);
            //echo "output: ".$output." \n";

            $files = glob (base_path($path.$song->id.".*"));
            //if file exists, save
            if(count($files)){
                $filename  = basename($files[0]);
                $song->update([
                    'state_id' => config("constants.STATE_WITH_AUDIO"),
                    'file_url' => asset($path.$filename)
                ]);
                echo "done.  \n";
            } else {
                $song->update([
                    'state_id' => config("constants.STATE_SKIPPED")
                ]);
                echo "skipped.  \n";
            }


        }
        $endTime = time();
        echo "end (".($endTime - $startTime)." sec). \n";
    }

}

/*
 from webm to mp3:
ffmpeg -i public/audio/4NmPY1FewwU.webm -acodec libmp3lame -aq 4 public/audio/4NmPY1FewwU-webm.mp3

*/
