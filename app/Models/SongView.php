<?php

namespace App\Models;

class SongView extends Elegant
{
    protected $table= 'song_views';
     protected $fillable = [
         'entry_id',
         "from_ip"
     ];

    public function offer()
    {
        return $this->hasOne('App\Models\Song',"id","entry_id");
    }

}
