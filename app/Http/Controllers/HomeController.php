<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Song;
use App\Models\Tag;

class HomeController extends Controller
{
    public function boot()
    {

    }

    public function index()
    {
        $viewData = [
            'new' => (new Song())->getAll()
                ->where("state_id",config('constants.STATE_WITH_AUDIO'))
                ->orderBy("created_at","DESC")
                ->take(50)
                ->get(),
            'popular' => (new Song())->getAll()
                ->where("state_id",config('constants.STATE_WITH_AUDIO'))
                ->orderBy("views","DESC")
                ->take(50)
                ->get(),
            'hot_tags' => (new Tag())->getHotTags()->take(80)->get()

        ];
        return $this->customResponse("home",$viewData);
    }
    public function rules()
    {
        return $this->customResponse("rules");
    }
}

