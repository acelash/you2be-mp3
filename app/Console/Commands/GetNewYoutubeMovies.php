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

    protected $signature = 'getmovies';

    protected $wordsToReplace = [
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
        'Official Lyric Video',
        '(Official Lyric Video)',
        'With Lyrics',
    ];

    protected $inserted = 0;

    protected $description = '...';

    public function handle()
    {
        echo "starting...\n";

        $goal = config("constants.YOUTUBE_GRABBER_PORTION");
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
        while(isset($search['info']['nextPageToken']) && $this->inserted <= $goal) {
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
                $thumbnail_path = "";
                $thumbnail_mini_path = "";
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

                    // scoated durata in sec
                    $videoInfo['duration'] = $duration[0]*60+$duration[1];

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
                    echo "video " . $source_id . " saved. \n";

                } catch (\Exception $e) {
                    DB::rollback();
                    if(File::exists($thumbnail_path)) unlink($thumbnail_path);
                    if(File::exists($thumbnail_mini_path)) unlink($thumbnail_mini_path);
                    echo "video " . $source_id . " not saved:{$e->getMessage()} \n";
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
