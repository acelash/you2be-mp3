<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\MovieComment;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function boot()
    {

    }

    public function index()
    {
        $viewData = [
            'active_movies' => (new Movie())->where('state_id',config('constants.STATE_ACTIVE'))->count(),
            'unchecked_movies' => (new Movie())->where('state_id',config('constants.STATE_UNCHECKED'))->count(),
            'unchecked_comments' => (new MovieComment())->getAll()
                ->where("movie_comment.state_id",config('constants.STATE_UNCHECKED'))
                ->orderBy("movie_comment.created_at","ASC")
                ->count()
        ];
        return $this->customResponse("admin.dashboard",$viewData);
    }
}
