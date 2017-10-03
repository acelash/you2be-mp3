<?php

namespace App\Console\Commands;


use Alaouy\Youtube\Facades\Youtube;
use App\Models\Movie;
use App\Models\Song;
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
            'part' => 'id',//snippet
            'maxResults' => config("constants.YOUTUBE_GRABBER_PAGE")
        );

        $search = Youtube::searchAdvanced($params, true);

        if (array_key_exists('results', $search)) {
            $this->processSearchResults($search['results']);
            $pagesPassed++;
        }
        echo "page passed. \n";

        // Check if we have a pageToken
        while(isset($search['info']['nextPageToken']) && $pagesPassed < $pagesToPass) {
            $params['pageToken'] = $search['info']['nextPageToken'];
            $search = Youtube::searchAdvanced($params, true);
            if (array_key_exists('results', $search)) {
                $this->processSearchResults($search['results']);
            }
            echo "page passed. \n";
            $pagesPassed++;
        }
    }
    protected function processSearchResults($results){
            foreach ($results as $video) {
                // ne uitam daca nu a fost parsat deja
                $source_id = $video->id->videoId;

                $existing = (new Song())->where('source_id', $source_id)->count();
                if ($existing) {
                    echo "video " . $source_id . " skipped. \n";
                    continue;
                }
                //pregatim informatiile despre video
                $videoInfo = [
                    'user_id' => config("constants.ROBOT_USER_ID"),
                    'state_id' => config("constants.STATE_UNCHECKED"),
                    'source_id' => $source_id,
                ];

                // votes
                $details = Youtube::getVideoInfo($source_id);
                if ($details) {
                    echo "processing video ".$source_id." \n";
                    // salvam doar din categoria muzica
                    if(property_exists($details->snippet, 'categoryId')
                        &&
                        $details->snippet->categoryId !== '10' // музыка
                        &&
                        $details->snippet->categoryId !== '24'){ //развлечения
                        echo "video " . $source_id . " skipped. wrong category: ".$details->snippet->categoryId." \n";
                        continue;
                    }

                    //duration
                    preg_match_all('/(\d+)/',$details->contentDetails->duration,$parts);
                    $duration = $parts[0];
                    if(count($duration) !== 2) {
                        echo "video " . $source_id . " skipped. >1h duration \n";
                        continue;
                    }
                    // nu salvam daca are mai mult de n minute
                    if($duration[count($duration)-2] > 8) {
                        echo "video " . $source_id . " skipped. >8 min duration \n";
                        continue;
                    }

                    $videoInfo['source_title'] = $details->snippet->title;
                    $videoInfo['title'] = $this->prepareTitle($details->snippet->title);
                    $videoInfo['source_description'] = $details->snippet->description;

                    // likes, dislikes , views
                    if(property_exists($details, 'statistics')){
                        if(property_exists($details->statistics, 'likeCount')){
                            $videoInfo['likes'] = $details->statistics->likeCount;
                            $videoInfo['dislikes'] = $details->statistics->dislikeCount;
                        }
                        if(property_exists($details->statistics, 'viewCount')){
                            $videoInfo['views'] = $details->statistics->viewCount;
                        }
                    }

                    // tags, de facut
                    if(property_exists($details->snippet, 'tags')){
                        $tags = $details->snippet->tags; // array of tags
                    }
                }

                $new = (new Song())->newInstance();
                $new->fill($videoInfo);

                DB::beginTransaction();
                try {
                    $new->save();

                    DB::commit();
                    echo "video " . $source_id . " saved. \n";

                } catch (\Exception $e) {
                    DB::rollback();
                    echo "video " . $source_id . " not saved:{$e->getMessage()} \n";
                }
            }

    }
    protected  function prepareTitle($source_title){
        $title = $source_title;



        return $title;
    }
}
