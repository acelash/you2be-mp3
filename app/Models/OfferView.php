<?php

namespace App\Model;

class OfferView extends Elegant
{
    protected $table= 'offer_views';
     protected $fillable = [
         'offer_id',
         "from_ip"
     ];

    public function offer()
    {
        return $this->hasOne('App\Model\Offer',"id","offer_id");
    }

}
