<?php

namespace App\Models;

class SongComment extends Elegant
{
    protected $table = "song_comment";
    protected $fillable = [
        'state_id',
        'user_id',
        'song_id',
        'text',
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
            ->addSelect("songs.title AS song_title")
            ->addSelect("users.avatar AS user_avatar")
            ->join("states", "states.id", "=", "song_comment.state_id")
            ->join("songs", "songs.id", "=", "song_comment.song_id")
            ->join("users", "users.id", "=", "song_comment.user_id");


        return $query;
    }

    public function getById($id)
    {
        $query = parent::getById($id)
            ->addSelect("states.name AS state")
            ->join("states", "states.id", "=", "song_comment.state_id");

        return $query;
    }

}
