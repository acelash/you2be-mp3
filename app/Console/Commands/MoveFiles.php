<?php

namespace App\Console\Commands;


use App\Models\Movie;
use App\Models\Song;
use Illuminate\Console\Command;

class MoveFiles extends Command
{

    protected $signature = 'movemp3';

    protected $description = '...';

    public function handle()
    {
        //ini_set('display_errors', 1);
        echo "starting...\n";
        $startTime = time();
        $path = "/public/audio/";

        $songs = (new Song())
            ->where("state_id", config("constants.STATE_WITH_AUDIO"))
            ->take(30)
            ->get();

        if ($songs) {

            try {

                foreach ($songs AS $song) {
                    $file = base_path() .$path.basename($song->file_url);
                    echo "processing " . $file." \n";

                    if (function_exists('curl_file_create')) { // php 5.5+
                        $cFile = curl_file_create($file);
                    } else { //
                        $cFile = '@' . realpath($file);
                    }
                    $post =  array(
                        'file' => $cFile,
                        'key'  => md5(config("constants.REMOTE_SERVER_KEY").basename($song->file_url)),
                        'name'  => $song->id,
                    );
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,'http://s73204.smrtp.ru/move.php');
                    curl_setopt($ch, CURLOPT_POST,1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                    $result=curl_exec ($ch);
                    curl_close ($ch);

                    if($result == "done"){
                        $song->update([
                            'state_id'=> config("constants.STATE_MOVED"),
                            'file_url' => "http://s73204.smrtp.ru/songs/".basename($song->file_url)
                        ]);
                        unlink($file);
                        echo "successfully uploaded {$song->id}\n";
                    } else {
                        echo "There was a problem while uploading {$song->id}\n";
                        var_dump($result);
                    }
                    sleep(3);
                }

            } catch (Exception  $e) {
                echo 'Error occurred: ', $e->getMessage(), "\n";
            }
        }

        $endTime = time();
        echo "end (".($endTime - $startTime)." sec). \n";
    }
}
