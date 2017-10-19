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
        echo "starting...\n";
        $startTime = time();
        $path = "/public/audio/";
        $remotePath = "/www/s73204.smrtp.ru/songs/";

        $ftp_server = config("constants.REMOTE_FTP_SERVER");
        $ftp_username = config("constants.REMOTE_FTP_USERNAME");
        $ftp_password = config("constants.REMOTE_FTP_PASSWORD");

        $songs = (new Song())
            ->where("state_id", config("constants.STATE_WITH_AUDIO"))
            ->take(20)
            ->get();

        if ($songs) {

            try {
                // set up basic connection
                $conn_id = ftp_connect($ftp_server);
                ftp_pasv($conn_id, true);
                // login with username and password
                $login_result = ftp_login($conn_id, $ftp_username, $ftp_password);

                foreach ($songs AS $song) {
                    echo "processing " . $song->id." \n";
                    $file = base_path() .$path.basename($song->file_url);
                    $remote_file = $remotePath.basename($song->file_url);

                    // upload a file
                    if (ftp_put($conn_id, $remote_file, $file, FTP_ASCII)) {
                        $song->update([
                            'state_id'=> config("constants.STATE_MOVED"),
                            'file_url' => "http://s73204.smrtp.ru/songs/".basename($song->file_url)
                        ]);
                        echo "successfully uploaded {$song->id}\n";
                    } else {
                        echo "There was a problem while uploading {$song->id}\n";
                    }
                }

                // close the connection
                ftp_close($conn_id);
            } catch (Exception  $e) {
                echo 'Error occurred: ', $e->getMessage(), "\n";
            }
        }

        $endTime = time();
        echo "end (".($endTime - $startTime)." sec). \n";
    }
}
