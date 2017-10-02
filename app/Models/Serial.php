<?php

namespace App\Models;

class Serial extends Elegant
{
    protected $table = "serials";
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
