<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class MovieVote extends Elegant
{
    protected $table = 'movie_vote';
    protected $fillable = [
        'type',
        'vote',
        'user_id',
        'movie_id',
    ];

    public function hasLiked($user_id, $movie_id)
    {
        $votes = (new MovieVote())
            ->where("user_id",$user_id)
            ->where("movie_id",$movie_id)
            ->where('type',DB::raw(1)) //pozitiv
            ->count();

        return $votes ? true : false;
    }
    public function hasDisliked($user_id, $movie_id)
    {
        $votes = (new MovieVote())
            ->where("user_id",$user_id)
            ->where("movie_id",$movie_id)
            ->where('type',DB::raw(2)) //negativ
            ->count();

        return $votes ? true : false;
    }
}
