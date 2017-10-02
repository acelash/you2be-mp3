<?php

namespace App\Models;

class Star extends Elegant
{


    protected $table = 'stars';
    protected $fillable = [
        'firstname',
        'lastname',
        'birth_date',
        'country_id',
        'photo',
        'bio',
    ];

}
