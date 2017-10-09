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
use Illuminate\Support\Facades\DB;

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

        /*$similar = (new Song())->getSimilar($entity)
            ->take(10);
        $similar = $similar->get();*/

        $similar = (new Song())->getAll()
            ->whereIn("songs.state_id", [
                config('constants.STATE_WITH_AUDIO'),
            ])
            ->where("songs.id", "<>", DB::raw($entity->id))
            ->search($entity->title)->take(10)->get();

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

        $entity = (new Tag())->getById($id)->get()->first();
        if (!$entity) abort(404);

        $songs = (new Song())->getAll()
            ->join("song_tag",function($join) use ($id){
                $join->on('song_tag.song_id','=','songs.id');
                $join->on('song_tag.tag_id','=',DB::raw($id));
            })
            ->whereIn("songs.state_id", [
                config('constants.STATE_WITH_AUDIO'),
            ])
          ->paginate(50);

        $viewData = [
            'entity' => $entity,
            'songs' => $songs,
            'hot_tags' => (new Tag())->getHotTags()->take(50)->get()
        ];
        return $this->customResponse("{$this->templateDirectory}.tag", $viewData);
    }

    public function search()
    {
        $query = trim($this->request->get('q'));

        if (strlen($query) < 2) redirect()->route('home_' . app()->getLocale());

        $songs = (new Song())->getAll()
            ->whereIn("songs.state_id", [
                config('constants.STATE_WITH_AUDIO'),
            ])
            ->search($query)->paginate(50);

        $viewData = [
            'query' => $query,
            'songs' => $songs,
            'hot_tags' => (new Tag())->getHotTags()->take(80)->get()
        ];
        return $this->customResponse("{$this->templateDirectory}.search", $viewData);
    }

    public function newSongs()
    {
        $viewData = [
            'sorted' => 'new',
            'songs' => (new Song())->getAll()
                ->where("state_id",config('constants.STATE_WITH_AUDIO'))
                ->orderBy("source_created_at","DESC")
                ->paginate(50),
            'hot_tags' => (new Tag())->getHotTags()->take(80)->get()

        ];
        return $this->customResponse("home",$viewData);
    }
    public function popular()
    {
        $viewData = [
            'sorted' => 'popular',
            'songs' => (new Song())->getAll()
                ->where("state_id",config('constants.STATE_WITH_AUDIO'))
                ->orderBy("views","DESC")
                ->paginate(50),
            'hot_tags' => (new Tag())->getHotTags()->take(80)->get()

        ];
        return $this->customResponse("home",$viewData);
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

