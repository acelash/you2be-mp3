<?php

namespace App\Models;

class RoleUser extends Elegant
{


    public $timestamps = false;
    protected $table = 'role_user';
    protected $fillable = [
        'role_id', 'user_id'
    ];

}
