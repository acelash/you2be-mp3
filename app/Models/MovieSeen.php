<?php

namespace App\Models;

class MovieSeen extends Elegant
{
    protected $table = 'movie_seen';
    protected $fillable = [
        'user_id',
        'movie_id',
    ];
    public function hasSeen($user_id, $movie_id)
    {
        $entry = (new MovieSeen())
            ->where("user_id",$user_id)
            ->where("movie_id",$movie_id)
            ->count();

        return $entry ? true : false;
    }
}
