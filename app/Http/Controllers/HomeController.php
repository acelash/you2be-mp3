<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Song;

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
                ->orderBy("created_at","DESC")
                ->take(50)
                ->get()
        ];
        return $this->customResponse("home",$viewData);
    }
    public function rules()
    {
        return $this->customResponse("rules");
    }
}

