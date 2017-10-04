<?php

namespace App\Models;

class Tag extends Elegant
{


    public $timestamps = false;
    protected $table = 'tags';
    protected $fillable = [
        'name'
    ];

}
