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
                ->where("state_id", config('constants.STATE_WITH_AUDIO'))
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
                ->paginate(50),
            'hot_tags' => (new Tag())->getHotTags()->take(80)->get()

        ];
        return $this->customResponse("home", $viewData);
    }

    public function rules()
    {
        return $this->customResponse("rules");
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

