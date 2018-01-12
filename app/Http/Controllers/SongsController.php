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
use App\Models\SongDownload;
use App\Models\SongView;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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

        $similar = (new Song())->getAll()
            ->whereIn("songs.state_id", [
                config('constants.STATE_WITH_AUDIO'),
                config('constants.STATE_MOVED')
            ])
            ->where("songs.id", "<>", DB::raw($entity->id))
            ->search($entity->title)->take(10)->get();

        $viewData = [
            'entity' => $entity,
            'similar' => $similar,
            'hot_tags' => (new Tag())->getHotTags()->take(config("constants.HOT_TAGS_TOTAL"))->get()
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
            ->join("song_tag", function ($join) use ($id) {
                $join->on('song_tag.song_id', '=', 'songs.id');
                $join->on('song_tag.tag_id', '=', DB::raw($id));
            })
            ->whereIn("songs.state_id", [
                config('constants.STATE_WITH_AUDIO'),
                config('constants.STATE_MOVED')
            ])
            ->paginate(50);

        $viewData = [
            'entity' => $entity,
            'songs' => $songs,
            'hot_tags' => (new Tag())->getHotTags()->take(config("constants.HOT_TAGS_TOTAL"))->get()
        ];
        return $this->customResponse("{$this->templateDirectory}.tag", $viewData);
    }

    public function preSearch()
    {
        $query = trim($this->request->get('q'));
        if (strlen($query) > 1)
           return redirect()->route('search_' . app()->getLocale(), ['q' => $query]);
        else
            return  redirect()->route('home_' . app()->getLocale());
    }

    public function search($q)
    {
        $query = trim($q);

        if (strlen($query) < 1)
            redirect()->route('home_' . app()->getLocale());

        $songs = (new Song())->getAll()
            ->whereIn("songs.state_id", [
                config('constants.STATE_WITH_AUDIO'),
                config('constants.STATE_MOVED')
            ])
            ->search($query)->paginate(50);

        $viewData = [
            'query' => $query,
            'songs' => $songs,
            'hot_tags' => (new Tag())->getHotTags()->take(config("constants.HOT_TAGS_TOTAL"))->get()
        ];
        return $this->customResponse("{$this->templateDirectory}.search", $viewData);
    }

    public function newSongs()
    {
        $viewData = [
            'sorted' => 'new',
            'songs' => (new Song())->getAll()
                ->whereIn("state_id", [
                    config('constants.STATE_WITH_AUDIO'),
                    config('constants.STATE_MOVED'),
                ])
                ->orderBy("source_created_at", "DESC")
                ->paginate(50),
            'hot_tags' => (new Tag())->getHotTags()->take(config("constants.HOT_TAGS_TOTAL"))->get()

        ];
        return $this->customResponse("home", $viewData);
    }

    public function popular()
    {
        $month = 60 * 60 * 24 * 30;
        $monthAgo = time() - $month;

        $viewData = [
            'sorted' => 'popular',
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
                ->paginate(50),
            'hot_tags' => (new Tag())->getHotTags()->take(config("constants.HOT_TAGS_TOTAL"))->get()

        ];
        return $this->customResponse("home", $viewData);
    }

    public function getDownloadLink($id)
    {
        $entity = $this->getModel()->find($id);
        if (!$entity) return ['status' => 'invalid id'];

        // daca fisierul exista, dam link la el
        $filePath  = base_path("public/audio/{$id}.mp3");
        if(File::exists($filePath)){
            return [
                'status' => 'ok'
            ];
        }
        // daca nu exista, apelam jobul si generam un mp3
        $path = "public/audio/";
        $filename  = $path.$entity->id.".%(ext)s";
        $domain = env("APP_ENV") == 'production' ? 'mp3cloud.su' : "music.cardeon.ru";
        $command = '/usr/local/bin/youtube-dl -o "/home/admin/web/'.$domain.'/public_html/'.$filename.'"  --extract-audio --audio-format mp3 --audio-quality 160K https://www.youtube.com/watch?v='.$entity->source_id;
        shell_exec($command);

        $files = glob (base_path($path.$entity->id.".mp3"));
        if(count($files)){
            $this->storeDownload($id);
            return [
                'status' => 'ok'
            ];
        } else {
            $entity->update([
                'state_id' => config("constants.STATE_SKIPPED")
            ]);
            return [
                'status' => 'false',
                'error' => "Error"
            ];
        }
    }

    public function downloadSong($id){
        $filePath  = base_path("public/audio/{$id}.mp3");
        $song = $this->getModel()->find($id);
        if(File::exists($filePath) && $song){
            $this->storeDownload($id);

            header('Content-type: audio/mpeg');
            header('Content-Disposition: attachment; filename="'.$song->title.' [mp3cloud.su].mp3"');
            readfile($filePath);
            die();
            //return response()->download($filePath, $song->title." [mp3cloud.su].mp3");
        } else {
            abort(404,"File not found");
        }
    }

    private function storeDownload($id){
        $ip = $this->request->ip();
        $viewExists = (new SongDownload())
            ->where("entry_id", $id)
            ->where("from_ip", $ip)
            ->get()
            ->first();
        if ($viewExists) {
            $viewExists->touch();
            return ['status' => 'view already Exists'];
        }
        $new = (new SongDownload())->newInstance();
        $new->fill([
            "entry_id" => $id,
            "from_ip" => $ip,
        ]);
        return $new->save();
    }

    public function storeView($id){

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
        return ['status' => 'done'];
    }
}

