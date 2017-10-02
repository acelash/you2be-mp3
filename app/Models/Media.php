<?php

namespace App\Models;

class Media extends Elegant
{
    protected $table = "media";
    protected $fillable = [
        'user_id',
        'producer_id',
        'title',
        'seo_title',
        'seo_description',
        'cover',
        'video_quality_id',
        'sound_quality_id',
        'year',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\User', "user_id");
    }

    public function getAll()
    {
        $query = parent::getAll();

        return $query;
    }
    public function getById($id)
    {
        $query = parent::getById($id);
        return $query;
    }

}
