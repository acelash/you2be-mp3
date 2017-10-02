<?php

namespace App\Models;

class Genre extends Elegant
{


    public $timestamps = false;
    protected $table = 'genres';
    protected $fillable = [
        'name',
        'seo_title',
        'seo_description',
    ];

}
