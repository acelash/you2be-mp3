<?php

namespace App\Console\Commands;


use Alaouy\Youtube\Facades\Youtube;
use App\Models\Movie;
use App\Models\Song;
use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class GetNewYoutubeMovies extends Command
{

    protected $signature = "getmovies {search}";

    protected $chars  = 'qwertyuiopasdfghjklzxcvbnm';
    protected $ru_chars  = 'йцукенгшщзхфывапролджэячсмитьбю';

    protected $wordsToReplace = [
        '(премьера клипа, 2017)',
        '(Новые Клипы 2017)',
        '(Новые Клипы 2017)',
        'Official Music Video',
        'Official Video',
        'Official Video 2017',
        '(Official Video 2017)',
        '[Official Video 2017]',
        '(Official Video)',
        '[Official Video]',
        '(official music video)',
        '2017',
        '(2017)',
        '()',
        '( )',
        '[]',
        '[ ]',
        'Music Video',
        '(Official Lyrics Video)',
        '(Official Lyric Video)',
        'Official Lyrics Video',
        'Official Lyric Video',
        'With Lyrics',
        'Lyrics',
        'Официальный Клип',
        'Клип',
        '(Audio)',
        '(Audio )',
        '( Audio )',
        '[Audio]',
        '[Audio ]',
        '[ Audio ]',
        '[Official Audio]',
        '(Official Audio)',
        '( Official Audio )',
        'Official Audio',

    ];

    protected $inserted = 0;

    protected $description = '...';

    private function getRandomQuery(){
        $rand = rand(1,3);
        $chars = $rand !== 1 ? $this->chars : $this->ru_chars;

        return $chars[rand(0, strlen($chars)-1)].$chars[rand(0, strlen($chars)-1)];
    }

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
            'order' => "date",
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

                echo $source_id . " ";

                $existing = (new Song())->where('source_id', $source_id)->count();
                if ($existing) {
                    echo "skip. \n";
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
                    //echo "processing ".$source_id." \n";

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


                    // scoated durata in sec
                    $videoInfo['duration'] = $duration[0]*60;

                    if(count($duration) < 2) {
                        $videoInfo['duration'] = $videoInfo['duration'] + $duration[1];
                    }

                    $publishedAt = $details->snippet->publishedAt;
                    $publishedAt = substr($publishedAt, 0, strpos($publishedAt, 'T'));
                    $videoInfo['source_created_at'] =  strtotime($publishedAt);

                    $thumbnails = $details->snippet->thumbnails;

                    // salvam imagine mini
                    $remoteUrl = $thumbnails->medium->url;
                    $imageName = $source_id . "." . config("constants.THUMBNAIL_EXTENSION");
                    $thumbnail_mini_path = base_path() . '/' . config("constants.THUMBNAIL_MINI_PATH") . $imageName;
                    file_put_contents($thumbnail_mini_path, file_get_contents($remoteUrl));
                    $videoInfo['thumbnail_mini'] = asset(config("constants.THUMBNAIL_MINI_PATH") . $imageName);

                    // salvam imagine medium
                    $remoteUrl = $thumbnails->medium->url;
                    $imageName = $source_id . "." . config("constants.THUMBNAIL_EXTENSION");
                    $thumbnail_path = base_path() . '/' . config("constants.THUMBNAIL_PATH") . $imageName;
                    file_put_contents($thumbnail_path, file_get_contents($remoteUrl));
                    $videoInfo['thumbnail'] = asset(config("constants.THUMBNAIL_PATH") . $imageName);

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
                    if(File::exists($thumbnail_path)) unlink($thumbnail_path);
                    if(File::exists($thumbnail_mini_path)) unlink($thumbnail_mini_path);
                    echo  $source_id . " not saved:{$e->getMessage()} \n";
                }
            }

    }
    protected  function prepareTitle($source_title){
        $title = $source_title;

        foreach ($this->wordsToReplace AS $word){
            $title =  str_replace($word,"",$title);
            $title =  str_replace(strtolower($word),"",$title);
            $title =  str_replace(strtoupper($word),"",$title);
        }

        return trim($title);
    }
    protected function prepareTagsIds($tags){
        $ids = [];
        foreach ($tags as $tag){
            $existing = (new Tag())->where("name",strtolower($tag))->get()->first();
            if($existing){
                array_push($ids,$existing->id);
            } else {
                $new = (new Tag())->newInstance();
                $new->fill(['name'=>strtolower($tag)]);
                $new->save();
                array_push($ids,$new->getKey());
            }
        }
        return $ids;
    }
}
