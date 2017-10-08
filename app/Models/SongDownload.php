<?php

namespace App\Models;

class SongDownload extends Elegant
{
    protected $table= 'song_download';
     protected $fillable = [
         'entry_id',
         "from_ip"
     ];

    public function offer()
    {
        return $this->hasOne('App\Models\Song',"id","entry_id");
    }

}
