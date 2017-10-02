<?php

namespace App\Models;

class MovieComment extends Elegant
{
    protected $table = "movie_comment";
    protected $fillable = [
        'state_id',
        'user_id',
        'movie_id',
        'title',
        'text',
        'type',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\User', "user_id");
    }

    public function getAll()
    {
        $query = parent::getAll()
            ->addSelect("states.name AS state")
            ->addSelect("users.name AS user_name")
            ->addSelect("movies.title AS movie_title")
            ->addSelect("users.avatar AS user_avatar")
            ->join("states", "states.id", "=", "movie_comment.state_id")
            ->join("movies", "movies.id", "=", "movie_comment.movie_id")
            ->join("users", "users.id", "=", "movie_comment.user_id");


        return $query;
    }

    public function getById($id)
    {
        $query = parent::getById($id)
            ->addSelect("states.name AS state")
            ->join("states", "states.id", "=", "movie_comment.state_id");

        return $query;
    }

}
