<?php

namespace App\Console\Commands;


use App\Models\Movie;
use App\Models\Song;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RemoveSkippedFiles extends Command
{

    protected $signature = 'removemp3';

    protected $description = '...';

    public function handle()
    {
        $startTime = time();

        $songs = (new Song())
            ->where("state_id", config("constants.STATE_SKIPPED"))
            ->take(100)
            ->get();

        if ($songs) {
            echo "found " . $songs->count() . ". \n";
            try {

                foreach ($songs AS $song) {
                    echo $song->id . " ";

                    // remove thumbnails
                    $this->removeThumbnails($song);

                    // remove audio file
                    $file_url = $song->file_url;
                    // daca fisierul e local
                    if (strpos($file_url, 'mp3cloud') !== false) {
                        $filePath = public_path('audio/' . $song->id . '.mp3');
                        if (File::exists($filePath)) {
                            unlink($filePath);
                            echo " mp3 \n";
                        }
                        $this->updateSongEntry($song);
                    } else {
                        // daca fisierul e remote
                        $post = array(
                            'key' => md5(config("constants.REMOTE_SERVER_KEY") . $song->id),
                            'song_id' => $song->id,
                        );
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, 'http://s73204.smrtp.ru/removemp3.php');
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                        $result = curl_exec($ch);
                        curl_close($ch);

                        if ($result == "done") {
                            $this->updateSongEntry($song);
                            echo " mp3  \n";
                        } else {
                            echo "ERROR \n";
                            var_dump($result);
                        }
                        sleep(3);
                    }
                }

            } catch (Exception  $e) {
                echo 'Error occurred: ', $e->getMessage(), "\n";
            }
        }

        $endTime = time();
        echo "end (" . ($endTime - $startTime) . " sec). \n";
    }

    private function removeThumbnails($song)
    {
        $thumbnail_mini = $song->thumbnail_mini;
        if ($thumbnail_mini) {
            $imagePath = public_path('images/thumbnails_mini/' . $song->source_id . '.jpg');
            if (File::exists($imagePath)) {
                unlink($imagePath);
                echo " thumbnail_mini ";
            }
        }

        $thumbnail = $song->thumbnail;
        if ($thumbnail) {
            $imagePath = public_path('images/thumbnails/' . $song->source_id . '.jpg');
            if (File::exists($imagePath)) {
                unlink($imagePath);
                echo " thumbnail ";
            }
        }
        return true;
    }

    private function updateSongEntry($song)
    {
        return $song->update([
            'state_id' => config("constants.STATE_HIDDEN"),
            'file_url' => " ",
            'title' => " ",
            'source_title' => " ",
            'thumbnail_mini' => " ",
            'thumbnail' => " ",
        ]);
    }
}
