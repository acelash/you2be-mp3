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
            'songs' => (new Song())->getAll()
                ->where("state_id",config('constants.STATE_WITH_AUDIO'))
                ->orderBy("views","DESC")
                ->paginate(50),
            'hot_tags' => (new Tag())->getHotTags()->take(80)->get()

        ];
        return $this->customResponse("home",$viewData);
    }
    public function rules()
    {
        return $this->customResponse("rules");
    }
}

