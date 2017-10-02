<?php

namespace App\Models;

class Country extends Elegant
{


    public $timestamps = false;
    protected $table = 'countries';
    protected $fillable = [
        'name',
        'seo_title',
        'seo_description',
    ];

}
