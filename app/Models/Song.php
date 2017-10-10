<?php

namespace App\Models;

use App\Extensions\SearchableTrait;
use Illuminate\Support\Facades\DB;

class Song extends Elegant
{
    use SearchableTrait;
    protected $table = "songs";
    protected $fillable = [
        'state_id',
        'user_id',
        'source_id',
        'title',
        'source_title',
        'source_created_at',
        'views',
        'likes',
        'dislikes',
        'file_url',
        'thumbnail',
        'thumbnail_mini',
        'duration',
    ];
    protected $searchable = [

        'columns' => [
            'songs.title' => 15,
           /* 'songs.seo_title' => 10,
            'movies.seo_description' => 10,
            'movies.year' => 5,*/
        ],
        'groupBy' => [
            'songs.id',
            'songs.state_id',
            'songs.user_id',
            'songs.source_id',
            'songs.title',
            'songs.source_title',
            'songs.source_created_at',
            'songs.views',
            'songs.likes',
            'songs.dislikes',
            'songs.file_url',
            'songs.thumbnail',
            'songs.thumbnail_mini',
            'songs.duration',
            'songs.created_at',
            'songs.updated_at',
        ]
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\User', "user_id");
    }

    public function downloads()
    {
        return $this->hasMany('App\Models\SongDownload', "entry_id", "id");
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'song_tag', 'song_id', 'tag_id');
    }

    public function getAll($all_states = false)
    {
        $query = parent::getAll();

        if (!$all_states) {
            $query->whereNotIn("songs.state_id", [
                config('constants.STATE_DRAFT'),
                config('constants.STATE_UNCHECKED'),
                config('constants.STATE_SKIPPED'),
            ]);
        }

        return $query;
    }

    public function getById($id, $all_states = false)
    {
        $query = parent::getById($id)
            ->addSelect("states.name AS state")
            ->join("states", "states.id", "=", "songs.state_id");

        if (!$all_states) {
            $query->whereNotIn("songs.state_id", [
                config('constants.STATE_DRAFT'),
                config('constants.STATE_UNCHECKED'),
                config('constants.STATE_SKIPPED'),
            ]);
        }


        return $query;
    }

    public function getAllDatatable()
    {
        $query = $this->select(
            "songs.id",
            "songs.source_title",
            "songs.created_at"
        )
            ->addSelect("states.name AS state")
            ->join("states", "states.id", "=", "songs.state_id")
            ->whereNotIn("songs.state_id", [
                config('constants.STATE_DRAFT'),
                config('constants.STATE_SKIPPED')
            ]);


        return $query;
    }

    public function getSimilar($song)
    {
        $query = $this->getAll();

        $query->whereIn("songs.state_id", [
            config('constants.STATE_WITH_AUDIO'),
        ]);

        $title = trim($song->title);

        $artists = explode("-",$title)[0];

        if($artists) {
            $artists_list = explode(" ",$artists);
            if(count($artists_list)){
                $first = true;
                foreach ($artists_list as $artist){
                        if(strlen($artist) > 1){
                            if($first){
                                $first = false;
                                $query->where("songs.title",'like','%'.$artist.'%');
                            } else {
                                $query->orWhere("songs.title",'like','%'.$artist.'%');
                            }
                        }
                }
            }
        } else {
            $words = explode(" ",$title);
            if(count($words)){
                $first = true;
                $query->where(function ($query) use ($words,$first){
                    foreach ($words as $word){
                        if(strlen($word) > 1){
                            if($first){
                                $first = false;
                                $query->where("songs.title",'like','%'.$word.'%');
                            } else {
                                $query->orWhere("songs.title",'like','%'.$word.'%');
                            }
                        }
                    }

                });
            }
        }



          $query->where("songs.id", "<>", DB::raw($song->id));

        return $query;
    }
}
