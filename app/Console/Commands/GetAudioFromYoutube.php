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
        $path = "public/audio/";

        $songs = (new Song())->getAll(true)
            ->where("songs.state_id",config("constants.STATE_CHECKED"))
            ->get();

        foreach ($songs as $song){
            $filename  = $path.$song->source_id.".mp3";
            shell_exec('youtube-dl -o "/home/admin/web/mp3.cardeon.ru/public_html/'.$filename.'"  --extract-audio --audio-format mp3 https://www.youtube.com/watch?v='.$song->source_id);
            $song->update([
                'state_id' => config("constants.STATE_WITH_AUDIO"),
                'file_path' => asset($filename)
            ]);
        }
    }

}
