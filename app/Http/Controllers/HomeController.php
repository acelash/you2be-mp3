<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function boot()
    {

    }

    public function index()
    {
        $viewData = [
            'popular_movies' => (new Movie())->getAll()->viewsTypes()->orderBy("views.total", "DESC")->take(12)->get(),
            'rating_movies' => (new Movie())
                ->getAll()
                ->ratingsTypes()
                ->orderBy(DB::raw("(positive_rating.total / (positive_rating.total + negative_rating.total))"), "DESC")
                ->orderBy(DB::raw("(positive_rating.total - negative_rating.total)"), "DESC")
                ->take(6)
                ->get(),
            'new_movies' => (new Movie())
                ->getAll()
                ->whereIn("movies.year",[date("Y",time())-2,date("Y",time())-1,date("Y",time())])
                ->orderBy("movies.created_at", "DESC")
                ->orderBy("movies.year", "DESC")
                ->take(12)
                ->get(),
        ];
        return $this->customResponse("home", $viewData);
    }
    public function rules()
    {
        return $this->customResponse("rules");
    }
}

