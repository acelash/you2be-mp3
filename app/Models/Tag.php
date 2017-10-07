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
                    GROUP BY tag_id
                ) as popularity"), "popularity.tag_id", "=", "tags.id")
            ->addSelect("popularity.total")
            ->where("active", 1)
            ->orderBy("popularity.total","DESC");

        return $query;
    }
}
