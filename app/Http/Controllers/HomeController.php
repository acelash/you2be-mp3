<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Song;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function boot()
    {

    }

    public function index()
    {
        $month = 60 * 60 * 24 * 30;
        $monthAgo = time() - $month;

        $viewData = [
            'songs' => (new Song())->getAll()
                ->whereIn("state_id", [
                    config('constants.STATE_WITH_AUDIO'),
                    config('constants.STATE_MOVED'),
                ])
                ->leftJoin(DB::raw("(
                    SELECT 
                        entry_id,
                        count(id) as total
                    FROM song_download
                    WHERE created_at >= '" . date('Y-m-d', $monthAgo) . "'
                    GROUP BY entry_id    
                ) as downloads"), "downloads.entry_id", "=", "songs.id")
                ->orderBy("downloads.total", "DESC")
                ->orderBy("views", "DESC")
                ->paginate(40),
            'hot_tags' => (new Tag())->getHotTags()->take(config("constants.HOT_TAGS_TOTAL"))->get()

        ];
        return $this->customResponse("home", $viewData);
    }

    public function copyrights()
    {
        return $this->customResponse("copyrights");
    }

    public function switchLang($locale)
    {
        $referer = $this->request->headers->get('referer');
        if ($referer && in_array($locale, config('app.locales'))) {

            $arr = explode("/", $referer);

            if (count($arr) >= 3 && strlen($arr[3]) > 1) {
                $arr[3] = $locale;
                $url = implode('/', $arr);
                return redirect($url);
            }

        }
        return redirect()->route('home_' . $locale);
    }
}

