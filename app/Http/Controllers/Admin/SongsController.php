<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\AdminEventValidation;
use App\Http\Requests\JobStoreValidation;
use App\Http\Requests\StoreMovie;
use App\Http\Requests\StoreOffer;
use App\Model\City;
use App\Model\Company;
use App\Model\EventTypes;
use App\Model\JobCategory;
use App\Model\Jobs;
use App\Model\JobSubCategory;
use App\Model\Offer;
use App\Model\OfferContact;
use App\Model\Region;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Song;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class SongsController extends Controller
{

    protected $templateDirectory = 'admin.song.';

    public function boot()
    {
        $this->setModel(new Song());
    }


    public function index()
    {
        return $this->customResponse($this->templateDirectory . "list");
    }

    public function datatable()
    {
        $offset = intval($this->request->get("start"));
        $limit = intval($this->request->get("length"));
        $search = $this->request->get("search");
        $orders = $this->request->get("order");

        $table = $this->getModel()->getTable();

        $columns = [
            "$table.id",
            "$table.title",
            "$table.thumbnail_medium",
            "$table.year",
            "$table.created_at",
            "state",
        ];

        $data = [];

        $query = $this->getModel()
            ->getAllDatatable();

        $total = $query->count();

        $query->skip($offset)
            ->take($limit);

        if ($search && $search['value']) {

            $query->where(function ($q) use ($search, $table) {
                $q->where("$table.title", 'like', "%{$search['value']}%");
                $q->orWhere("$table.year", 'like', "%{$search['value']}%");
                $q->orWhere("states.name", 'like', "%{$search['value']}%");
            });
        }

        // ordering
        if ($orders) {
            foreach ($orders as $order) {
                if (array_key_exists($order['column'], $columns)) {
                    $dir = ($order['dir'] == 'desc') ? $dir = 'desc' : $dir = 'asc';
                    $col = $columns[$order['column']];
                    $query->orderBy($col, $dir);
                }
            }
        }

        $items = $query->get();

        foreach ($items->toArray() as $item) {
            $values = array_values($item);
            $data[] = $values;
        }

        return [
            'draw' => $this->request->get("draw"),
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $data
        ];
    }

    public function show($id)
    {
        if (!$id) abort(404, "Entry not found.");

        $entry = $this->getModel()->getById($id,true)->get()->first();
        $viewData = [
            "genres" => (new Genre())->getAll()->orderBy("name")->get(),
            "countries" => (new Country())->getAll()->orderBy("name")->get(),
        ];
        $viewData['entry'] = $entry;

        return $this->customResponse($this->templateDirectory . "edit", $viewData);
    }

    public function update(StoreMovie $request, $id)
    {
        $entry = $this->getModel()->getById($id,true)
            ->get()
            ->first();


        $input = $request->all();
        $data = [
            'title' => $input['title'],
            'title_original' => $input['title_original'],
            'text' => $input['text'],
        ];

        if (array_key_exists('source_start_at', $input)) $data['source_start_at'] = $input['source_start_at'];
        if (array_key_exists('source_id', $input)) $data['source_id'] = $input['source_id'];
        if (array_key_exists('source_url', $input)) $data['source_url'] = $input['source_url'];
        if (array_key_exists('year', $input)) $data['year'] = $input['year'];

      /*  // salvam imagine default
        $file = $request->file("thumbnail_default");
        if ($file && $file->isValid()) {
            $destinationPath =  base_path() .'/'.config("constants.THUMBNAIL_DEFAULT_PATH");
            $fileName = $entry->source_id . '.' . $file->getClientOriginalExtension();
            if ($file->move($destinationPath, $fileName)) {
                $data['thumbnail_default'] = asset(config("constants.THUMBNAIL_DEFAULT_PATH") . $fileName);
            }
        }*/
        // salvam imagine medium
        $file = $request->file("thumbnail_medium");
        if ($file && $file->isValid()) {
            $destinationPath =  base_path() .'/'.config("constants.THUMBNAIL_MEDIUM_PATH");
            $fileName = $entry->source_id . '.' . $file->getClientOriginalExtension();
            if ($file->move($destinationPath, $fileName)) {

                $pathToImage = $destinationPath.$fileName;

                // resize image
                $resize = Image::make($pathToImage);
                $resize->heighten(config("constants.POSTER_HEIGHT"));
                // $img->insert('public/watermark.png');
                $resize->save($pathToImage);
                // end resize image

                //optimize image
                /*$optimizerChain = OptimizerChainFactory::create();
                $optimizerChain->optimize($pathToImage);*/

                $data['thumbnail_medium'] = asset(config("constants.THUMBNAIL_MEDIUM_PATH") . $fileName);
            }
        }
     /*   // salvam imagine high
        $file = $request->file("thumbnail_high");
        if ($file && $file->isValid()) {
            $destinationPath =  base_path() .'/'.config("constants.THUMBNAIL_HIGH_PATH");
            $fileName = $entry->source_id . '.' . $file->getClientOriginalExtension();
            if ($file->move($destinationPath, $fileName)) {
                $data['thumbnail_high'] = asset(config("constants.THUMBNAIL_HIGH_PATH") . $fileName);
            }
        }*/


        $genres = array_key_exists('genre_list', $input) ? $input['genre_list'] : [];
        $countries = array_key_exists('country_list', $input) ? $input['country_list'] : [];

        DB::beginTransaction();
        try {

            if ($entry->state_id == config('constants.STATE_DRAFT')) $data['state_id'] = config('constants.STATE_ACTIVE');

            switch ($input['save_mode']){
                case "skip_and_next":
                    $data['state_id'] = config('constants.STATE_SKIPPED');
                    break;
                case "check_and_next":
                    $data['state_id'] = config('constants.STATE_ACTIVE');
                    break;
            }

            $entry->update($data);
            $entry->genres()->sync($genres);
            $entry->countries()->sync($countries);

            DB::commit();

            if (($input['save_mode'] == "check_and_next") || ($input['save_mode'] == "skip_and_next")) {
                $nextUnchecked = (new Movie())->where("state_id", config('constants.STATE_UNCHECKED'))->get(["id"])->first();
                if ($nextUnchecked) {
                    return redirect()->action(
                        'Admin\MoviesController@show',
                        ['id' => $nextUnchecked->id]
                    );
                } else {
                    return redirect()->back()->with(["success" => true, "message" => "Saved. but no unchecked videos"]);
                }

            } else
                return redirect()->back()->with(["success" => true, "message" => trans('translate.saved')]);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->withInput($request->all())
                ->with(["success" => false, "message" => $e->getMessage()]);
        }

    }

    public function storeDraft()
    {
        $data = [
            'user_id' => auth()->id(),
            'state_id' => config('constants.STATE_DRAFT')
        ];


        $new = $this->getModel()->newInstance();
        $new->fill($data);
        $new->save();

        return redirect()->action(
            'Admin\MoviesController@show',
            ['id' => $new->getKey()]
        );
    }

    public function destroy($id)
    {
        $entry = $this->getModel()->find($id);
        if(!$entry)  return redirect()->back()->with(["success" => false, "message" => "not found "]);
        $thumbnail_default = $entry->thumbnail_default;
        $thumbnail_medium = $entry->thumbnail_medium;
        $thumbnail_high = $entry->thumbnail_high;

        DB::beginTransaction();
        try {
            $entry->genres()->sync([]);
            $entry->countries()->sync([]);
            $count = $this->getModel()->destroy($id);
            DB::commit();
            if ($count) {

                // stergem fisierele
                $localPath = base_path() . '/' . config("constants.THUMBNAIL_DEFAULT_PATH") . basename($thumbnail_default);
                if(File::exists($localPath)) unlink($localPath);
                $localPath = base_path() . '/' . config("constants.THUMBNAIL_MEDIUM_PATH") . basename($thumbnail_medium);
                if(File::exists($localPath)) unlink($localPath);
                $localPath = base_path() . '/' . config("constants.THUMBNAIL_HIGH_PATH") . basename($thumbnail_high);
                if(File::exists($localPath)) unlink($localPath);

                if ($this->request->get('no_return')) {
                    if($this->request->get('next_unckecked')){
                        $nextUnchecked = (new Movie())->where("state_id", config('constants.STATE_UNCHECKED'))->get(["id"])->first();
                        if($nextUnchecked){
                            return redirect()->action(
                                'Admin\MoviesController@show',
                                ['id' => $nextUnchecked->id]
                            );
                        } else {
                            return redirect("admin/movies")->with(["success" => true, "message" => "Removed, but no unchecked video."]);
                        }
                    } else {
                        return redirect("admin/movies")->with(["success" => true, "message" => "Removed"]);
                    }
                } else
                    return redirect()->back()->with(["success" => true, "message" => trans('translate.removed')]);
            } else {
                return redirect()->back()->with(["success" => false, "message" => trans('translate.error')]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(["success" => false, "message" =>$e->getMessage()]);
        }
    }
    public function check(){
        $nextUnchecked = (new Movie())->where("state_id", config('constants.STATE_UNCHECKED'))->get(["id"])->first();
        if($nextUnchecked){
            return redirect()->action(
                'Admin\MoviesController@show',
                ['id' => $nextUnchecked->id]
            );
        } else {
            return redirect("admin/movies")->with(["success" => true, "message" => "No unchecked video."]);
        }
    }
}
