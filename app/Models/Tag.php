<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Tag extends Elegant
{


    public $timestamps = false;
    protected $table = 'tags';
    protected $fillable = [
        'name'
    ];

    /**
     * @return $this
     */
    public function getHotTags()
    {
        $query = $this->getAll();
        $query->join(DB::raw("(
                    SELECT tag_id, COUNT(song_id) AS total
                    FROM song_tag
                    JOIN songs ON songs.id = song_tag.song_id
                    AND songs.state_id = ".config('constants.STATE_WITH_AUDIO')."
                    GROUP BY tag_id
                ) as popularity"), "popularity.tag_id", "=", "tags.id")
            ->addSelect("popularity.total")
            ->where("active", 1)
            ->orderBy("popularity.total","DESC");

        return $query;
    }
}
