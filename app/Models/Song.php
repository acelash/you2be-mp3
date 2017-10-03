<?php

namespace App\Models;

use App\Extensions\SearchableTrait;
use Illuminate\Support\Facades\DB;

class Song extends Elegant
{
    use SearchableTrait;
    protected $table = "songs";
    protected $fillable = [
        'state_id',
        'user_id',
        'source_id',
        'title',
        'source_title',
        'source_description',
        'views',
        'likes',
        'dislikes',
        'file_url',
    ];
    /*protected $searchable = [

        'columns' => [
            'movies.title' => 15,
            'movies.seo_title' => 10,
            'movies.seo_description' => 10,
            'movies.year' => 5,
        ],
        'groupBy' => [
            'movies.id',
            'movies.user_id',
            "movies.title",
            "movies.seo_title",
            "movies.seo_description",
            "movies.year",
        ]
    ];*/

    protected $dates = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\User', "user_id");
    }

    public function views()
    {
        return $this->hasMany('App\Models\MovieView', "entry_id", "id");
    }

    public function comments()
    {
        return $this->hasMany('App\Models\MovieComment', "movie_id", "id");
    }

    public function votes()
    {
        return $this->hasMany('App\Models\MovieVote', "vote_id", "id");
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'movie_genre', 'movie_id', 'genre_id');
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'movie_country', 'movie_id', 'country_id');
    }

    public function getAll($all_states = false)
    {
        $query = parent::getAll();

        if (!$all_states) {
            $query->whereNotIn("songs.state_id", [
                config('constants.STATE_DRAFT'),
                config('constants.STATE_UNCHECKED'),
                config('constants.STATE_SKIPPED'),
            ]);
        }

        return $query;
    }

    public function getById($id, $all_states = false)
    {
        $query = parent::getById($id)
            ->addSelect("states.name AS state")
            ->join("states", "states.id", "=", "songs.state_id");

        if (!$all_states) {
            $query->whereNotIn("songs.state_id", [
                config('constants.STATE_DRAFT'),
                config('constants.STATE_UNCHECKED'),
                config('constants.STATE_SKIPPED'),
            ]);
        }


        return $query;
    }

    public function getAllDatatable()
    {
        $query = $this->select(
            "songs.id",
            "songs.source_title",
            "songs.created_at"
        )
            ->addSelect("states.name AS state")
            ->join("states", "states.id", "=", "songs.state_id")
            ->whereNotIn("songs.state_id", [
                config('constants.STATE_DRAFT'),
                config('constants.STATE_SKIPPED')
            ]);


        return $query;
    }

    public function getNowWatching($movie = false)
    {
        $query = $this->getAll();

        $query->whereNotIn("movies.state_id", [
            config('constants.STATE_DRAFT'),
            config('constants.STATE_UNCHECKED'),
            config('constants.STATE_SKIPPED'),
        ]);

        if ($movie) $query->where("movies.id", "<>", DB::raw($movie->id));

        $query->take(config("constants.NOW_WATCHING_TOTAL"));

        return $query;
    }

    public function getSimilar($movie)
    {
        $genres = $movie->genres()->get()->pluck('id')->toArray();

        $query = $this->getAll();

        $query->join(DB::raw("(
            SELECT 
                count(movie_genre.id) AS total,
                movie_genre.movie_id
            FROM movie_genre
            WHERE movie_genre.genre_id IN (" . implode(",", $genres) . ")
            GROUP BY movie_genre.movie_id    
        ) AS genres"), "genres.movie_id", "=", "movies.id");
        $query->addSelect("genres.total AS same_genres");
        $query->orderBy("genres.total", "DESC");

        $query->whereNotIn("movies.state_id", [
            config('constants.STATE_DRAFT'),
            config('constants.STATE_UNCHECKED'),
            config('constants.STATE_SKIPPED'),
        ]);

        $query->where("movies.id", "<>", DB::raw($movie->id));

        return $query;
    }

    public function scopeViewsTypes($query, $period = "")
    {
        $time = time();
        $condition = "";
        switch ($period) {
            case 1:// 24 ore
                $diff = 60 * 60 * 24;
                break;
            case 2:// 1 saptamina
                $diff = 60 * 60 * 24 * 7;
                break;
            case 3:// 1 luna
                $diff = 60 * 60 * 24 * 30;
                break;
            default:
                $diff = 0;
        }

        if ($period) {
            $timeLimit = $time - $diff;
            $condition = "WHERE movie_views.updated_at >= '" . date("Y-m-d H:i:s", $timeLimit) . "'";
        }


        $query->leftJoin(DB::raw("(
            SELECT 
                COUNT(movie_views.id) AS total,
                movie_views.entry_id
            FROM movie_views
            " . $condition . "
            GROUP BY movie_views.entry_id
        ) AS views{$period}"), "views{$period}.entry_id", "movies.id")
            ->addSelect("views{$period}.total AS views{$period}");
        return $query;
    }

    public function scopeRatingsTypes($query, $period = "")
    {
        $time = time();
        $condition = "";
        switch ($period) {
            case 1:// 24 ore
                $diff = 60 * 60 * 24;
                break;
            case 2:// 1 saptamina
                $diff = 60 * 60 * 24 * 7;
                break;
            case 3:// 1 luna
                $diff = 60 * 60 * 24 * 30;
                break;
            default:
                $diff = 0;
        }

        if ($period) {
            $timeLimit = $time - $diff;
            $condition = " AND movie_vote.updated_at >= '" . date("Y-m-d H:i:s", $timeLimit) . "'";
        }

        // Positive rating
        $query->leftJoin(DB::raw("(
            SELECT 
                SUM(movie_vote.vote) AS total,
                movie_vote.movie_id
            FROM movie_vote
            WHERE movie_vote.type = 1 /* positive */
              " . $condition . "
            GROUP BY movie_vote.movie_id
        ) AS positive_rating{$period}"), "positive_rating{$period}.movie_id", "movies.id")
            ->addSelect("positive_rating{$period}.total AS positive_rating{$period}");

        // negative rating
        $query->leftJoin(DB::raw("(
            SELECT 
                SUM(movie_vote.vote) AS total,
                movie_vote.movie_id
            FROM movie_vote
            WHERE movie_vote.type = 2 /* negative */
              " . $condition . "
            GROUP BY movie_vote.movie_id
        ) AS negative_rating{$period}"), "negative_rating{$period}.movie_id", "movies.id")
            ->addSelect("negative_rating{$period}.total AS negative_rating{$period}");

        return $query;
    }
}
