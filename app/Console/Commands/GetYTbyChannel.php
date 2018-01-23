<?php

namespace App\Console\Commands;

//UC2pmfLm7iq6Ov1UwYrWYkZA = vevo

use Alaouy\Youtube\Facades\Youtube;
use App\Extensions\YoutubeApiHelper;
use App\Models\Movie;
use App\Models\Song;
use App\Models\YtChannel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GetYTbyChannel extends Command
{
    use YoutubeApiHelper;

    protected $signature = "getbychannel {channel_id}";

    protected $inserted = 0;

    protected $description = '...';

    public function handle()
    {
        //echo "starting...\n";
        $startTime = time();

        $goal = config("constants.YOUTUBE_GRABBER_PORTION_CHANNEL");
        $pagesPassed = 0;

        $channel_id = $this->argument('channel_id');

        if($channel_id == 0){
            $channel = (new YtChannel())
                ->where("active",1)
                ->orderBy("parsed_at","ASC")->get()->first();
            if($channel){
                $channel_id = $channel['channel_id'];
            }
        }

        echo "channel=".$channel_id." \n";

        $params = array(
            'channelId' => $channel_id,
            'type' => 'video',
            'part' => 'id',//snippet
            //'videoDuration' => 'long',// medium 4 .. 20 min
            'maxResults' => config("constants.YOUTUBE_GRABBER_PAGE"),
            'order' => "date", //date,rating
            'videoCategoryId'=> '10' // music
        );

        $search = Youtube::searchAdvanced($params, true);

        if (array_key_exists('results', $search) && is_array($search['results'])) {
            $this->processSearchResults($search['results']);
            $pagesPassed++;
        }
        // Check if we have a pageToken
        while(isset($search['info']['nextPageToken']) && $this->inserted <= $goal) {
            $params['pageToken'] = $search['info']['nextPageToken'];
            $search = Youtube::searchAdvanced($params, true);
            if (array_key_exists('results', $search)) {
                if(is_array($search['results']))
                $this->processSearchResults($search['results']);
                else {
                    echo "inserted: ".$this->inserted."videos \n";
                    die();
                }
            }
            $pagesPassed++;
        }

        $updateChannelEntry = (new YtChannel());
        $updateChannelEntry->where("channel_id",$channel_id)->update(['parsed_at'=>time()]);

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
                  //  echo "skip. \n";
                    continue;
                }
                //pregatim informatiile despre video
                $videoInfo = [
                    'user_id' => config("constants.ROBOT_USER_ID"),
                    'state_id' => config("constants.STATE_DRAFT"),
                    'source_id' => $source_id,
                ];

                // votes
                $details = Youtube::getVideoInfo($source_id);
                if ($details) {
                    echo "processing ".$source_id." \n";

                    if(strpos($details->contentDetails->duration,"H")){
                        echo  " skip. > 1h \n";
                        continue;
                    }

                    if(!strpos($details->contentDetails->duration,"M")){
                       echo  " skip. < 1m \n";
                        continue;
                    }

                    //duration
                    preg_match_all('/(\d+)/',$details->contentDetails->duration,$parts);
                    $duration = $parts[0];

                    // nu salvam daca are mai mult de n minute
                    if($duration[0] > 8) {
                        echo  " skip. > 8 min \n";
                        continue;
                    }

                    // nu salvam daca are mai putin de n minute
                    if($duration[0] < 2) {
                        echo  " skip. < 2 min \n";
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
                        }
                        if(property_exists($details->statistics, 'viewCount')){
                            $videoInfo['views'] = $details->statistics->viewCount;
                        }
                    }
                }

                $new = (new Song())->newInstance();
                $new->fill($videoInfo);

                DB::beginTransaction();
                try {
                    $new->save();

                    // tags
                    if(property_exists($details->snippet, 'tags')){
                        $tags = $details->snippet->tags; // array of tags
                        $tagsIds = $this->prepareTagsIds($tags);
                        $new->tags()->sync($tagsIds);
                    }

                    $this->inserted++;

                    DB::commit();
                    echo   " SAVED. \n";

                } catch (\Exception $e) {
                    DB::rollback();
                    echo  $source_id . " not saved:{$e->getMessage()} \n";
                }
            }

    }
}
