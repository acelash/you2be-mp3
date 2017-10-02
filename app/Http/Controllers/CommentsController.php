<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Models\MovieComment;
use Illuminate\Support\Facades\DB;

class CommentsController extends Controller
{
    protected $templateDirectory = 'comment.';

    public function boot()
    {
        $this->setModel(new MovieComment());
    }

    public function store(StoreComment $request)
    {
        $input = $request->all();
        $referrer = $this->request->headers->get('referer');

        $input['user_id'] = auth()->id();
        $input['state_id'] = config("constants.STATE_UNCHECKED");

        $new = $this->getModel()->newInstance();
        $new->fill($input);
        DB::beginTransaction();
        try {
            $new->save();

            DB::commit();
            return redirect($referrer."#add_comment")->with(["success" => true, "message" => "Ваш отзыв успешно добавлен!"]);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect($referrer."#add_comment")->with(["success" => false, "message" => $e->getMessage()]);
        }

    }
}

