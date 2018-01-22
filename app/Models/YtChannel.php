<?php

namespace App\Models;

class YtChannel extends Elegant
{


    public $timestamps = false;
    protected $table = 'yt_channels';
    protected $fillable = [
        'channel_id',
        'parsed_at',
    ];

}
