<?php

namespace App\Models;

class MovieWatchLater extends Elegant
{
    protected $table = 'movie_watch_later';
    protected $fillable = [
        'user_id',
        'movie_id',
    ];
    public function hasWatchLater($user_id, $movie_id)
    {
        $entry = (new MovieWatchLater())
            ->where("user_id",$user_id)
            ->where("movie_id",$movie_id)
            ->count();

        return $entry ? true : false;
    }
}
