<?php

namespace App\Console\Commands;


use Alaouy\Youtube\Facades\Youtube;
use App\Models\Movie;
use App\Models\MovieVote;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GetNewYoutubeMovies extends Command
{

    protected $signature = 'getmovies';

    protected $description = '...';

    public function handle()
    {
        echo "starting...\n";

        $pagesToPass = config("constants.YOUTUBE_GRABBER_PAGES");
        $pagesPassed = 0;

        $params = array(
            'q' => config("constants.YOUTUBE_GRABBER_QUERY"),
            'type' => 'video',
            'part' => 'id, snippet',
            'maxResults' => config("constants.YOUTUBE_GRABBER_PAGE")
        );

        $search = Youtube::searchAdvanced($params, true);
        if (array_key_exists('results', $search)) {
            $this->processSearchResults($search['results']);
            $pagesPassed++;
        }

        // Check if we have a pageToken
        while(isset($search['info']['nextPageToken']) && $pagesPassed < $pagesToPass) {
            $params['pageToken'] = $search['info']['nextPageToken'];
            $search = Youtube::searchAdvanced($params, true);
            if (array_key_exists('results', $search)) {
                $this->processSearchResults($search['results']);
            }
            $pagesPassed++;
        }
    }
    protected function processSearchResults($results){
            foreach ($results as $video) {
              /*  $thumbnail_path_default = "";
                $thumbnail_path_medium = "";
                $thumbnail_path_high = "";*/
                // ne uitam daca nu a fost parsat deja
                $source_id = $video->id->videoId;

                $existing = (new Movie())->where('source_id', $source_id)->count();
                if ($existing) {
                    echo "video " . $source_id . " skipped. \n";
                    continue;
                }
                //pregatim informatiile despre video
                $videoInfo = [
                    'user_id' => config("constants.ROBOT_USER_ID"),
                    'state_id' => config("constants.STATE_UNCHECKED"),
                    'source_id' => $source_id,
                    'title' => $video->snippet->title,
                    'text' => $video->snippet->description,
                ];

                /*
                $thumbnails = $video->snippet->thumbnails;

                // salvam imagine default
                $remoteUrl = $thumbnails->default->url;
                $imageName = $source_id . "." . config("constants.THUMBNAIL_EXTENSION");
                $thumbnail_path_default = base_path() . '/' . config("constants.THUMBNAIL_DEFAULT_PATH") . $imageName;
                file_put_contents($thumbnail_path_default, file_get_contents($remoteUrl));
                $videoInfo['thumbnail_default'] = asset(config("constants.THUMBNAIL_DEFAULT_PATH") . $imageName);
                // salvam imagine medium
                $remoteUrl = $thumbnails->medium->url;
                $imageName = $source_id . "." . config("constants.THUMBNAIL_EXTENSION");
                $thumbnail_path_medium = base_path() . '/' . config("constants.THUMBNAIL_MEDIUM_PATH") . $imageName;
                file_put_contents($thumbnail_path_medium, file_get_contents($remoteUrl));
                $videoInfo['thumbnail_medium'] = asset(config("constants.THUMBNAIL_MEDIUM_PATH") . $imageName);
                // salvam imagine high
                $remoteUrl = $thumbnails->high->url;
                $imageName = $source_id . "." . config("constants.THUMBNAIL_EXTENSION");
                $thumbnail_path_high = base_path() . '/' . config("constants.THUMBNAIL_HIGH_PATH") . $imageName;
                file_put_contents($thumbnail_path_high, file_get_contents($remoteUrl));
                $videoInfo['thumbnail_high'] = asset(config("constants.THUMBNAIL_HIGH_PATH") . $imageName);
                */

                // votes
                $details = Youtube::getVideoInfo($source_id);
                if ($details && property_exists($details->statistics, 'likeCount')) {
                    $positiveVotes = $details->statistics->likeCount;
                    $negativeVotes = $details->statistics->dislikeCount;
                    // transformam in procente si reducem nr la 100 de voturi
                    $totalVotes = intval($positiveVotes) + intval($negativeVotes);

                    if($positiveVotes > 0){
                        $newVotesPositive = (new MovieVote())->newInstance();
                        $votesInfoPositive = [
                            'user_id' => config("constants.ROBOT_USER_ID"),
                            'type' => 1 // positive
                        ];
                        $positiveProcent = ($positiveVotes / $totalVotes) * 100;
                        $votesInfoPositive['vote'] = ceil($positiveProcent);

                    }
                    if($negativeVotes > 0){
                        $newVotesNegative = (new MovieVote())->newInstance();
                        $votesInfoNegative = [
                            'user_id' => config("constants.ROBOT_USER_ID"),
                            'type' => 2 // negative
                        ];
                        $negativeProcent = ($negativeVotes / $totalVotes) * 100;
                        $votesInfoNegative['vote'] = ceil($negativeProcent);
                    }



                }

                $new = (new Movie())->newInstance();
                $new->fill($videoInfo);

                DB::beginTransaction();
                try {
                    $new->save();

                    if(isset($votesInfoPositive)){
                        $votesInfoPositive['movie_id'] = $new->getKey();
                        $newVotesPositive->fill($votesInfoPositive);
                        $newVotesPositive->save();
                    }

                    if(isset($votesInfoNegative)){
                        $votesInfoNegative['movie_id'] = $new->getKey();
                        $newVotesNegative->fill($votesInfoNegative);
                        $newVotesNegative->save();
                    }

                    DB::commit();
                    echo "video " . $source_id . " saved. \n";

                } catch (\Exception $e) {
                    DB::rollback();
                    echo "video " . $source_id . " not saved:{$e->getMessage()} \n";
                   /*
                    // stergem fisierele daca nu s-a salvat filmul
                    if(File::exists($thumbnail_path_default)) unlink($thumbnail_path_default);
                    if(File::exists($thumbnail_path_medium)) unlink($thumbnail_path_medium);
                    if(File::exists($thumbnail_path_high)) unlink($thumbnail_path_high);*/
                }
            }

    }
}
