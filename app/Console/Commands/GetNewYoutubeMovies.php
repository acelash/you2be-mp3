<?php

namespace App\Console\Commands;


use Alaouy\Youtube\Facades\Youtube;
use App\Extensions\YoutubeApiHelper;
use App\Models\Movie;
use App\Models\Song;
use App\Models\YtChannel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class GetNewYoutubeMovies extends Command
{
    use YoutubeApiHelper;

    protected $signature = "getmovies {search}";

    protected $inserted = 0;

    protected $description = '...';

    public function handle()
    {
        //echo "starting...\n";
        $startTime = time();

        $goal = config("constants.YOUTUBE_GRABBER_PORTION");
        $pagesPassed = 0;

        $randomQuery = ($this->argument('search') !=='0')  ? $this->argument('search') : $this->getRandomQuery();
        echo "q=".$randomQuery." \n";

        $params = array(
            'q' => $randomQuery,//config("constants.YOUTUBE_GRABBER_QUERY"),
            'type' => 'video',
            'part' => 'id',//snippet
            //'videoDuration' => 'long',// medium 4 .. 20 min
            'maxResults' => config("constants.YOUTUBE_GRABBER_PAGE"),
            'order' => "viewCount", //date,rating
            'videoCategoryId'=> '10' // music
        );

        $search = Youtube::searchAdvanced($params, true);

        if (array_key_exists('results', $search) && is_array($search['results'])) {
            $this->processSearchResults($search['results']);
            $pagesPassed++;
        } else {
            $randomQuery = $this->getRandomQuery();
            $params['q'] = $randomQuery;
            echo "no results, try again . q= ".$randomQuery." \n";
            $search = Youtube::searchAdvanced($params, true);
            if (array_key_exists('results', $search) && is_array($search['results'])) {
                $this->processSearchResults($search['results']);
                $pagesPassed++;
            }
        }

        // Check if we have a pageToken
        while(isset($search['info']['nextPageToken']) && $this->inserted <= $goal) {
            $params['pageToken'] = $search['info']['nextPageToken'];
            $search = Youtube::searchAdvanced($params, true);
            if (array_key_exists('results', $search)) {
                if(is_array($search['results']))
                $this->processSearchResults($search['results']);
                else {
                    echo "no results.\n inserted: ".$this->inserted."videos \n";
                    die();
                }
            }
            $pagesPassed++;
        }

        $endTime = time();
        echo "end (".($endTime - $startTime)." sec). \n";
    }
    protected function processSearchResults($results){
            foreach ($results as $video) {
                $thumbnail_path = "";
                $thumbnail_mini_path = "";
                // ne uitam daca nu a fost parsat deja
                $source_id = $video->id->videoId;

               // echo $source_id . " ";

                $existing = (new Song())->where('source_id', $source_id)->count();
                if ($existing) {
                    echo "skip. \n";
                    continue;
                }
                //pregatim informatiile despre video
                $videoInfo = [
                    'user_id' => config("constants.ROBOT_USER_ID"),
                    'state_id' => config("constants.STATE_WITH_AUDIO"),
                    'source_id' => $source_id,
                ];

                // votes
                $details = Youtube::getVideoInfo($source_id);

                if ($details) {
                    //echo "processing ".$source_id." \n";

                    if(strpos($details->contentDetails->duration,"H")){
                       // echo  " skip. > 1h \n";
                        continue;
                    }

                    if(!strpos($details->contentDetails->duration,"M")){
                      //  echo  " skip. < 1m \n";
                        continue;
                    }

                    //duration
                    preg_match_all('/(\d+)/',$details->contentDetails->duration,$parts);
                    $duration = $parts[0];

                    // nu salvam daca are mai mult de n minute
                    if($duration[0] > 8) {
                     //   echo  " skip. > 8 min \n";
                        continue;
                    }

                    // nu salvam daca are mai putin de n minute
                    if($duration[0] < 2) {
                      //  echo  " skip. < 2 min \n";
                        continue;
                    }

                    // scoated durata in sec
                    $videoInfo['duration'] = $duration[0]*60;

                    if(count($duration) > 1) {
                        $videoInfo['duration'] = $videoInfo['duration'] + $duration[1];
                    }

                    $publishedAt = $details->snippet->publishedAt;
                    $publishedAt = substr($publishedAt, 0, strpos($publishedAt, 'T'));
                    $videoInfo['source_created_at'] =  strtotime($publishedAt);

                    $videoInfo['source_title'] = $details->snippet->title;
                    $videoInfo['title'] = $this->prepareTitle($details->snippet->title);

                    // likes, dislikes , views
                    if(property_exists($details, 'statistics')){
                        if(property_exists($details->statistics, 'likeCount')){
                            $videoInfo['likes'] = $details->statistics->likeCount;
                            $videoInfo['dislikes'] = $details->statistics->dislikeCount;

                            if($videoInfo['likes'] < config("constants.MINIM_LIKES")) {
                              //  echo  " skip. <".config("constants.MINIM_LIKES")." likes \n";
                                continue;
                            }
                        }
                        if(property_exists($details->statistics, 'viewCount')){
                            $videoInfo['views'] = $details->statistics->viewCount;

                            if($videoInfo['views'] < config("constants.MINIM_VIEWS")) {
                              //  echo  " skip. < ".config("constants.MINIM_VIEWS")." views \n";
                                continue;
                            }
                        }
                    }
                }

                $new = (new Song())->newInstance();
                $new->fill($videoInfo);

                $existingChannel = (new YtChannel())->where('channel_id',$details->snippet->channelId)->count();

                if(!$existingChannel){
                    $newChannel = (new YtChannel())->newInstance();
                    $newChannel->fill([
                        'channel_id' => $details->snippet->channelId
                    ]);
                }


                DB::beginTransaction();
                try {
                    $new->save();
                    if(isset($newChannel)) $newChannel->save();

                    // tags
                    if(property_exists($details->snippet, 'tags')){
                        $tags = $details->snippet->tags; // array of tags
                        $tagsIds = $this->prepareTagsIds($tags);
                        $new->tags()->sync($tagsIds);
                    }

                    $this->inserted++;

                    DB::commit();
                //    echo   " SAVED. \n";

                } catch (\Exception $e) {
                    DB::rollback();
                    if(File::exists($thumbnail_path)) unlink($thumbnail_path);
                    if(File::exists($thumbnail_mini_path)) unlink($thumbnail_mini_path);
                    echo  $source_id . " not saved:{$e->getMessage()} \n";
                }
            }

    }

}
