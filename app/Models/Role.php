<?php

namespace App\Models;

class Role extends Elegant
{


    public $timestamps = false;
    protected $table = 'roles';
    protected $fillable = [
        'name'
    ];

}
