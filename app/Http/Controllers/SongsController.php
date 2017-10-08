<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\MovieComment;
use App\Models\MovieSeen;
use App\Models\MovieView;
use App\Models\MovieVote;
use App\Models\MovieWatchLater;
use App\Models\Song;
use App\Models\SongComment;
use App\Models\SongView;
use App\Models\Tag;

class SongsController extends Controller
{
    protected $templateDirectory = 'song.';

    public function boot()
    {
        $this->setModel(new Song());
    }

    public function show($slug)
    {
        $id = getIdFromSlug($slug);
        if (!$id) abort(404);

        $entity = $this->getModel()->getById($id)->get()->first();
        if (!$entity) abort(404);

        $similar =  (new Song())->getSimilar($entity)
            ->take(10);
        //dd($similar->toSql());
        $similar = $similar->get();

        $viewData = [
            'entity' => $entity,
            'similar' => $similar,
            'hot_tags' => (new Tag())->getHotTags()->take(80)->get()
        ];
        return $this->customResponse("{$this->templateDirectory}.show", $viewData);
    }
    public function tag($slug)
    {
        $id = getIdFromSlug($slug);
        if (!$id) abort(404);

        $entity = $this->getModel()->getById($id)->get()->first();
        if (!$entity) abort(404);

        $viewData = [
            'entity' => $entity,
            'hot_tags' => (new Tag())->getHotTags()->take(50)->get()
        ];
        return $this->customResponse("{$this->templateDirectory}.tag", $viewData);
    }

    /*public function storeView($id)
    {
        $entity = $this->getModel()->find($id);
        if (!$entity) return ['status' => 'invalid id'];

        $ip = $this->request->ip();

        $viewExists = (new SongView())
            ->where("entry_id", $id)
            ->where("from_ip", $ip)
            ->get()
            ->first();
        if ($viewExists) {
            $viewExists->touch();
            return ['status' => 'view already Exists'];
        }

        $new = (new SongView())->newInstance();
        $new->fill([
            "entry_id" => $id,
            "from_ip" => $ip,
        ]);
        $new->save();

        return [
            'status' => 'ok'
        ];
    }*/


}

