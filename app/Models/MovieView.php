<?php

namespace App\Models;

class MovieView extends Elegant
{
    protected $table= 'movie_views';
     protected $fillable = [
         'entry_id',
         "from_ip"
     ];

    public function offer()
    {
        return $this->hasOne('App\Models\Movie',"id","entry_id");
    }

}
