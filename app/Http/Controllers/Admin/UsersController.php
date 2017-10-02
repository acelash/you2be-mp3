<?php

namespace App\Http\Controllers\Admin;


use App\Extensions\Notificate;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserValidation;
use App\Models\Role;
use App\User;
use Illuminate\Support\Facades\DB;


class UsersController extends Controller
{
    public function boot()
    {
    }

    public function index()
    {
        return $this->customResponse("admin.user.list");
    }

    public function datatable()
    {
        $offset = intval($this->request->get("start"));
        $limit = intval($this->request->get("length"));
        $search = $this->request->get("search");
        $orders = $this->request->get("order");

        $columns = [
            "users.id",
            "users.avatar",
            "users.name",
            "users.email",
            DB::raw("CASE WHEN role_user.id > 0 THEN 1 ELSE 0 END"),
            "users.created_at",
        ];

        $data = [];
        $total = (new User())->count();
        $query = (new User())
            ->skip($offset)
            ->take($limit);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search['value']}%");
                $q->orWhere('email', 'like', "%{$search['value']}%");
            });
        }

        $query->leftJoin("role_user", function ($join) {
            $join->on("role_user.role_id", "=", DB::raw(config("constants.ROLE_ADMIN")));
            $join->on("role_user.user_id", "=", DB::raw("users.id"));
        });

        // ordering
        if ($orders) {
            foreach ($orders as $order) {
                if (array_key_exists($order['column'], $columns)) {
                    $dir = ($order['dir'] == 'desc') ? $dir = 'desc' : $dir = 'asc';
                    $query->orderBy($columns[$order['column']], $dir);
                }
            }
        }

        //dd($query->toSql());
        $users = $query->get($columns);

        foreach ($users->toArray() as $user) {
            $values = array_values($user);
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

        $entity = (new User())->find($id);
        $roles = (new Role())->getAll()
            ->leftJoin("role_user", function ($join) use ($id) {
                $join->on("role_user.role_id", "=", "roles.id");
                $join->on("role_user.user_id", "=", DB::raw($id));
            })
            ->addSelect(DB::raw("CASE WHEN role_user.id > 0 THEN 1 ELSE 0 END as checked"))
            ->get();

        return $this->customResponse("admin.user.edit", [
            'user' => $entity,
            'roles' => $roles
        ]);

    }

    public function update($id, UserValidation $request)
    {
        $input = $request->all();
        $data = [];

        if (array_key_exists("name", $input)) $data['name'] = $input['name'];
        if (array_key_exists("email", $input)) $data['email'] = $input['email'];
        if (array_key_exists("ban", $input) && $input['ban']) $data['banned_till'] = time() + intval($input['ban']);

        $roles = array_key_exists("roles", $input) ? $input['roles'] : [];
        $user = (new User())->find($id);

        $file = $request->file("avatar");

        if ($file && $file->isValid()) {
            $destinationPath = base_path() . config("constants.AVATARS_PATH"); // upload path
            $fileName = $user->id . "-" . '.' . $file->getClientOriginalExtension();

            if ($file->move($destinationPath, $fileName)) {
                $data['avatar'] = env("APP_URL") . config("constants.AVATARS_PATH") . $fileName;
            }
        }

        DB::beginTransaction();
        try {
            $user->update($data);
            $user->roles()->sync($roles);

            DB::commit();
            return redirect($request->server("HTTP_REFERER"))->with(["success" => true, "message" => trans('translate.saved')]);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect($request->server("HTTP_REFERER"))->with(["success" => false, "message" => $e->getMessage()]);
        }

    }

    public function destroy($id)
    {
        $count = (new User())->destroy($id);
        if ($count) {
            return redirect($this->request->server("HTTP_REFERER"))->with(["success" => true, "message" => trans('translate.removed')]);
        } else {
            return redirect($this->request->server("HTTP_REFERER"))->with(["success" => false, "message" => trans('translate.error')]);
        }
    }
}
