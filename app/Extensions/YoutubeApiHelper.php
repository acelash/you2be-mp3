<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 05.10.2016
 * Time: 21:33
 */
namespace App\Extensions;


use App\Model\Candidate;
use App\Model\Settings;
use App\Models\Tag;

trait YoutubeApiHelper
{
    protected $chars  = 'qwertyuiopasdfghjklzxcvbnm';
    protected $ru_chars  = 'йцукенгшщзхфывапролджэячсмитьбю';

    protected $wordsToReplace = [
        '(премьера клипа, 2017)',
        '(Новые Клипы 2017)',
        '(Новые Клипы 2017)',
        'Official Music Video',
        'Official Video',
        'Official Video 2017',
        '(Official Video 2017)',
        '[Official Video 2017]',
        '(Official Video)',
        '[Official Video]',
        '(official music video)',
        '2017',
        '(2017)',
        '()',
        '( )',
        '[]',
        '[ ]',
        'Music Video',
        '(Official Lyrics Video)',
        '(Official Lyric Video)',
        'Official Lyrics Video',
        'Official Lyric Video',
        'With Lyrics',
        'Lyrics',
        'Официальный Клип',
        'Клип',
        '(Audio)',
        '(Audio )',
        '( Audio )',
        '[Audio]',
        '[Audio ]',
        '[ Audio ]',
        '[Official Audio]',
        '(Official Audio)',
        '( Official Audio )',
        'Official Audio',

    ];

    protected  function prepareTitle($source_title){
        $title = $source_title;

        foreach ($this->wordsToReplace AS $word){
            $title =  str_replace($word,"",$title);
            $title =  str_replace(strtolower($word),"",$title);
            $title =  str_replace(strtoupper($word),"",$title);
        }

        return trim($title);
    }
    protected function prepareTagsIds($tags){
        $ids = [];
        foreach ($tags as $tag){
            $existing = (new Tag())->where("name",strtolower($tag))->get()->first();
            if($existing){
                array_push($ids,$existing->id);
            } else {
                $new = (new Tag())->newInstance();
                $new->fill(['name'=>strtolower($tag)]);
                $new->save();
                array_push($ids,$new->getKey());
            }
        }
        return $ids;
    }
    private function getRandomQuery(){
        $rand = rand(1,3);
        $chars = $rand !== 1 ? $this->chars : $this->ru_chars;

        return $chars[rand(0, strlen($chars)-1)].$chars[rand(0, strlen($chars)-1)];
    }
}