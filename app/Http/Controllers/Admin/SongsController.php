<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovie;
use App\Http\Requests\StoreSong;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Song;
use Illuminate\Support\Facades\DB;

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
            "$table.thumbnail",
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

        $entry = $this->getModel()->getById($id, true)->get()->first();
        $viewData = [

        ];
        $viewData['entry'] = $entry;

        return $this->customResponse($this->templateDirectory . "edit", $viewData);
    }

    public function update(StoreSong $request, $id)
    {
        $entry = $this->getModel()->getById($id, true)
            ->get()
            ->first();


        $input = $request->all();
        $data = [
            'title' => $input['title']
        ];

        DB::beginTransaction();
        try {

            switch ($input['save_mode']) {
                case "skip_and_next":
                    $data['state_id'] = config('constants.STATE_SKIPPED');
                    break;
                case "check_and_next":
                    $data['state_id'] = config('constants.STATE_CHECKED');
                    break;
            }

            $entry->update($data);

            DB::commit();

            if (($input['save_mode'] == "check_and_next") || ($input['save_mode'] == "skip_and_next")) {
                $nextUnchecked = (new Song())->where("state_id", config('constants.STATE_MOVED'))->get(["id"])->first();
                if ($nextUnchecked) {
                    return redirect()->action(
                        'Admin\SongsController@show',
                        ['id' => $nextUnchecked->id]
                    );
                } else {
                    return redirect()->back()->with(["success" => true, "message" => "Saved. but no unchecked entries"]);
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
            'Admin\SongsController@show',
            ['id' => $new->getKey()]
        );
    }

    public function destroy($id)
    {
        $entry = $this->getModel()->find($id);
        if (!$entry) return redirect()->back()->with(["success" => false, "message" => "not found "]);

        DB::beginTransaction();
        try {

            $count = $this->getModel()->destroy($id);
            DB::commit();
            if ($count) {
                if ($this->request->get('no_return')) {
                    if ($this->request->get('next_unckecked')) {
                        $nextUnchecked = (new Movie())->where("state_id", config('constants.STATE_UNCHECKED'))->get(["id"])->first();
                        if ($nextUnchecked) {
                            return redirect()->action(
                                'Admin\SongsController@show',
                                ['id' => $nextUnchecked->id]
                            );
                        } else {
                            return redirect("admin/songs")->with(["success" => true, "message" => "Removed, but no unchecked entries."]);
                        }
                    } else {
                        return redirect("admin/songs")->with(["success" => true, "message" => "Removed"]);
                    }
                } else
                    return redirect()->back()->with(["success" => true, "message" => trans('translate.removed')]);
            } else {
                return redirect()->back()->with(["success" => false, "message" => trans('translate.error')]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(["success" => false, "message" => $e->getMessage()]);
        }
    }

    public function approve()
    {
        $viewData = [];
        $viewData['songs'] = (new Song())
            ->getAll()
            ->whereIn("state_id", [
                config('constants.STATE_MOVED'),
                config('constants.STATE_WITH_AUDIO')
            ])
            ->where("approved", 0)
            ->orderBy("songs.id", "ASC")
            ->take(50)
            ->get();

        return $this->customResponse($this->templateDirectory . "approve", $viewData);
    }

    public function storeApprove($id, $type)
    {
        $song = (new Song())->find($id);
        if ($type == 1) {
            $song->update(['approved' => 1]);
            return [
                'status' => 'skipped'
            ];
        } else {
            $song->update(['approved' => 1, "state_id" => config('constants.STATE_SKIPPED')]);
            return [
                'status' => 'approved'
            ];
        }
    }

}
