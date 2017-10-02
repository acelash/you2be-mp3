<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\AdminEventValidation;
use App\Http\Requests\JobCategoryValidation;
use App\Http\Requests\JobStoreValidation;
use App\Http\Requests\StoreList;
use App\Model\EventTypes;
use App\Model\JobCategory;
use App\Model\Jobs;
use App\Models\Country;
use Illuminate\Support\Facades\DB;


class CountriesController extends Controller
{

    protected $templateDirectory = 'admin.country.';
    public function boot()
    {
        $this->setModel(new Country());
    }

    public function index()
    {
        return $this->customResponse($this->templateDirectory."list");
    }
    public function showAddForm()
    {
        return $this->customResponse($this->templateDirectory."new");
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
            "$table.name",
        ];

        $data = [];

        $query = $this->getModel()
            ->getAll();

        $total = $query->count();

        $query->skip($offset)
            ->take($limit);

        if ($search && $search['value']) {
            $query->where(function ($q) use ($search,$table) {
                $q->where("$table.name", 'like', "%{$search['value']}%");
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

        $items = $query->get($columns);

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
        if (intval($id) <= 0) return ['success' => false];
        $entity = $this->getModel()->find($id);

        return $this->customResponse($this->templateDirectory."edit", [
            'entity' => $entity
        ]);

    }

    public function store(StoreList $request)
    {
        $input = $request->all();

        $new = $this->getModel()->newInstance();
        $new->fill($input);
        DB::beginTransaction();
        try {
            $new->save();

            DB::commit();
            return redirect()->back()->with(["success" => true, "message" => trans('translate.saved')]);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(["success" => false, "message" => $e->getMessage()]);
        }

    }

    public function update($id, StoreList $request)
    {
        $input = $request->all();

        $update = $this->getModel()->find($id);

        DB::beginTransaction();
        try {
            $update->update($input);

            DB::commit();
            return redirect()->back()->with(["success" => true, "message" => trans('translate.saved')]);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(["success" => false, "message" => $e->getMessage()]);
        }

    }

    public function destroy($id)
    {
        $count = $this->getModel()->destroy($id);
        if ($count) {
            return redirect()->back()->with(["success" => true, "message" => trans('translate.removed')]);
        } else {
            return redirect()->back()->with(["success" => false, "message" => trans('translate.error')]);
        }
    }
}
