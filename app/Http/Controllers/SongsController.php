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

        /*$liked = false;
        $disliked = false;
        $seen = false;
        $watch_later = false;*/

        // daca e logat, vedem ce a mai facut useru ista cu filmu
      /*  if (auth()->check()) {
            $liked = (new MovieVote())->hasLiked(auth()->id(), $entity->id);
            $disliked = (new MovieVote())->hasDisliked(auth()->id(), $entity->id);
            $seen = (new MovieSeen())->hasSeen(auth()->id(), $entity->id);
            $watch_later = (new MovieWatchLater())->hasWatchLater(auth()->id(), $entity->id);
        }*/

        $viewData = [
          /*  'liked' => $liked,
            'disliked' => $disliked,
            'seen' => $seen,
            'watch_later' => $watch_later,*/
            'entity' => $entity,
            'comments' => (new MovieComment())->getAll()->where('movie_id', $entity->id)->get(),
            /*'now_watching' => (new Movie())->getNowWatching($entity)->get(),
            'similar_movies' => (new Movie())->getSimilar($entity)->get()->take(5),*/
        ];
        return $this->customResponse("{$this->templateDirectory}.show", $viewData);
    }

    public function showCatalog($slug = '', $id = 0)
    {
        $filter = [];
        $movieQuery = (new Movie())->getAll();
        $input = $this->request->all();

        // filtrare
        // daca vine cu un filtru aparte prin slug
        if (trim(strlen($slug)) > 0 && intval($id) > 0) {
            //filtram doar dupa acel parametru
            switch ($slug) {
                case "genre":
                    $filter['genres'] = [$id];
                    $moviesWithGenre = collect(DB::select("SELECT movie_genre.movie_id FROM movie_genre WHERE movie_genre.genre_id = " . intval($id)))->pluck('movie_id')->toArray();
                    $movieQuery->whereIn("movies.id", count($moviesWithGenre) ? $moviesWithGenre : [0]);
                    break;
                case "country":
                    $filter['countries'] = [$id];
                    $moviesWithCountry = collect(DB::select("SELECT movie_country.movie_id FROM movie_country WHERE movie_country.country_id = " . intval($id)))->pluck('movie_id')->toArray();
                    $movieQuery->whereIn("movies.id", count($moviesWithCountry) ? $moviesWithCountry : [0]);
                    break;
                case "year":
                    // aici $id poate fi un sir de ani
                    $years = array_map('intval', explode(',', $id));
                    if (count($years) > 1) {
                        // e un sir
                        $filter['years'] = $years;
                        $movieQuery->whereIn("movies.year", $years);
                    } else {
                        // daca e un numar
                        $filter['years'] = [$id];
                        $movieQuery->where("movies.year", intval($id));
                    }
                    break;
            }
            //daca vine filtrul normal, filtram dupa toti parametrii din filtru
        } else if (array_key_exists('filter', $input) && strlen($input['filter']) > 0) {
            $filter = json_decode(base64_decode($input['filter']), true);

            if (array_key_exists('genres', $filter)) {
                if (count($filter['genres'])) {
                    $moviesWithGenre = collect(DB::select("SELECT movie_genre.movie_id FROM movie_genre WHERE movie_genre.genre_id IN (" . implode(',', $filter['genres']) . ")"))->pluck('movie_id')->toArray();
                    $movieQuery->whereIn("movies.id", count($moviesWithGenre) ? $moviesWithGenre : [0]);
                } else unset($filter['genres']);
            }

            if (array_key_exists('countries', $filter)) {
                if (count($filter['countries'])) {
                    $moviesWithCountry = collect(DB::select("SELECT movie_country.movie_id FROM movie_country WHERE movie_country.country_id IN (" . implode(',', $filter['countries']) . ")"))->pluck('movie_id')->toArray();
                    $movieQuery->whereIn("movies.id", count($moviesWithCountry) ? $moviesWithCountry : [0]);
                } else unset($filter['countries']);
            }

            if (array_key_exists('years', $filter)) {
                $selectedYears = $filter['years'];
                if (count($filter['years'])) {
                    $movieQuery->whereIn("movies.year", $selectedYears);
                } else unset($filter['years']);
            }

        }

        //sortare
        $sortType = "";
        if (array_key_exists('sort', $input)) {
            $sortType = $input['sort'];
            $sort = explode("_", $input['sort']);
            switch ($sort[0]) {
                case 'new':
                    $movieQuery->orderBy("movies.created_at", "DESC");
                    break;
                case 'popular':
                    if (array_key_exists(1, $sort)) {
                        $movieQuery->viewsTypes(intval($sort['1']));
                        $movieQuery->orderBy("views" . intval($sort['1']), "DESC");
                    } else {
                        $movieQuery->viewsTypes();
                        $movieQuery->orderBy("views", "DESC");
                    }
                    break;
                case 'rating':
                    if (array_key_exists(1, $sort)) {
                        $movieQuery->ratingsTypes(intval($sort['1']));
                        $movieQuery->orderBy(DB::raw("(positive_rating" . intval($sort['1']) . ".total / (positive_rating" . intval($sort['1']) . ".total + negative_rating" . intval($sort['1']) . ".total))"), "DESC");
                        $movieQuery->orderBy(DB::raw("(positive_rating" . intval($sort['1']) . ".total - negative_rating" . intval($sort['1']) . ".total)"), "DESC");
                    } else {
                        $movieQuery->ratingsTypes();
                        $movieQuery->orderBy(DB::raw("(positive_rating.total / (positive_rating.total + negative_rating.total))"), "DESC");
                        $movieQuery->orderBy(DB::raw("(positive_rating.total - negative_rating.total)"), "DESC");
                    }
                    break;
            }
        } else {
            $movieQuery->orderBy("movies.created_at", "DESC");
            $sortType = "new";
        }

        // mod vizualizare
        $viewMode = array_key_exists('view', $input) ? $input['view'] : config("constants.DEFAULT_VIEW_MODE");
        if ($sortType !== "rating" && $viewMode == 2) {
            $movieQuery->ratingsTypes();
        }

        $years = (new Movie())->getAll()->get()->pluck('year')->unique()->toArray();
        rsort($years);// sortam descrescator anii
        $viewData = [
            'filter' => [
                'genres' => (new Genre())->getAll()->get(),
                'countries' => (new Country())->getAll()->get(),
                'years' => $years,
                'search' => $filter
            ],
            'sort' => $sortType,
            'view_mode' => $viewMode,
            'now_watching' => (new Movie())->getNowWatching()->get(),
            'movies' => $movieQuery->paginate(config("constants.MOVIES_CATALOG_ON_PAGE"))
        ];

        return $this->customResponse("{$this->templateDirectory}.catalog", $viewData);
    }

    public function storeView($id)
    {
        $entity = $this->getModel()->find($id);
        if (!$entity) return ['status' => 'invalid id'];

        $ip = $this->request->ip();

        $viewExists = (new MovieView())
            ->where("entry_id", $id)
            ->where("from_ip", $ip)
            ->get()
            ->first();
        if ($viewExists) {
            $viewExists->touch();
            return ['status' => 'view already Exists'];
        }

        $new = (new MovieView())->newInstance();
        $new->fill([
            "entry_id" => $id,
            "from_ip" => $ip,
        ]);
        $new->save();

        return [
            'status' => 'ok'
        ];
    }

    public function toggleLikes($movie_id, $type)
    {
        // ne uitam daca a votat deja
        $vote = (new MovieVote())
            ->where("user_id", auth()->id())
            ->where("movie_id", $movie_id)
            ->get()
            ->first();

        // daca a mai votat, schimbam tipul votului
        if ($vote) {
            $vote->type = intval($type);
            $vote->save();
        } else {
            // inseram un vot
            $newVote = (new MovieVote())->newInstance();
            $newVote->fill([
                'user_id' => auth()->id(),
                'movie_id' => $movie_id,
                'type' => $type,
                'vote' => 1
            ]);
            $newVote->save();
        }

        // redirectam la filmul care a fost votat
        $movie = $this->getModel()->find($movie_id);
        return redirect()->route('show_movie', [
            'slug' => prepareSlugUrl($movie->id, $movie->title)
        ]);

    }

    public function toggleWatchLater($movie_id)
    {

        // ne uitam daca e in lista
        $entry = (new MovieWatchLater())
            ->where("user_id", auth()->id())
            ->where("movie_id", $movie_id)
            ->get()
            ->first();

        // daca e in lista, stergem din lista
        if ($entry) {
            (new MovieWatchLater())->find($entry->id)->delete();
        } else {
            // inseram in lista
            $new = (new MovieWatchLater())->newInstance();
            $new->fill([
                'user_id' => auth()->id(),
                'movie_id' => $movie_id,
            ]);

            DB::beginTransaction();
            try {
                $new->save();
                (new MovieSeen())
                    ->where("user_id", auth()->id())
                    ->where("movie_id", $movie_id)
                    ->delete();

                DB::commit();
                return redirect()->back()->with(["success" => true, "message" => trans('translate.saved')]);
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->with(["success" => false, "message" => $e->getMessage()]);
            }

        }

        // redirectam la filmul care a fost votat
        $movie = $this->getModel()->find($movie_id);
        return redirect()->route('show_movie', [
            'slug' => prepareSlugUrl($movie->id, $movie->title)
        ]);

    }

    public function toggleSeen($movie_id)
    {

        // ne uitam daca e in lista
        $entry = (new MovieSeen())
            ->where("user_id", auth()->id())
            ->where("movie_id", $movie_id)
            ->get()
            ->first();

        // daca e in lista, stergem din lista
        if ($entry) {
            (new MovieSeen())->find($entry->id)->delete();
        } else {
            // inseram in lista
            $new = (new MovieSeen())->newInstance();
            $new->fill([
                'user_id' => auth()->id(),
                'movie_id' => $movie_id,
            ]);

            DB::beginTransaction();
            try {
                $new->save();
                // stergem din watch later daca este
                (new MovieWatchLater())
                    ->where("user_id", auth()->id())
                    ->where("movie_id", $movie_id)
                    ->delete();

                DB::commit();
                return redirect()->back()->with(["success" => true, "message" => trans('translate.saved')]);
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->with(["success" => false, "message" => $e->getMessage()]);
            }

        }

        // redirectam la filmul care a fost votat
        $movie = $this->getModel()->find($movie_id);
        return redirect()->route('show_movie', [
            'slug' => prepareSlugUrl($movie->id, $movie->title)
        ]);

    }
}

