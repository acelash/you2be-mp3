<?php

namespace App\Models;

class State extends Elegant
{


    public $timestamps = false;
    protected $table = 'states';
    protected $fillable = [
        'name'
    ];

}
