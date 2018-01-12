<?php

namespace App\Models;

class SongView extends Elegant
{
    protected $table= 'song_view';
     protected $fillable = [
         'entry_id',
         "from_ip"
     ];
}
