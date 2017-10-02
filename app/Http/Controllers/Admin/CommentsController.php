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
use App\Models\Genre;
use App\Models\MovieComment;
use App\User;
use Illuminate\Support\Facades\DB;


class CommentsController extends Controller
{

    protected $templateDirectory = 'admin.comment.';
    public function boot()
    {
        $this->setModel(new MovieComment());
    }

    public function moderate()
    {
        $viewData = [
            'comments' => $this->getModel()->getAll()
                ->where("movie_comment.state_id",config('constants.STATE_UNCHECKED'))
            ->orderBy("movie_comment.created_at","ASC")
            ->get()
        ];
        return $this->customResponse($this->templateDirectory."moderate",$viewData);
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

    public function block($id)
    {
        // stergem toate comentariile userului si il banam
        $comment = $this->getModel()->find($id);
        $user = (new User())->find($comment->user_id);

        (new MovieComment())->where("user_id",$user->id)->delete();
        $user->banned_till = time() + 60*60*24*30; // 30 zile
        $user->save();
        return redirect()->back()->with(["success" => true, "message" => "User banned & all comments removed."]);
    }

    public function approve($id)
    {
        $comment = $this->getModel()->find($id);
        $comment->state_id = config("constants.STATE_ACTIVE");
        $comment->save();
        return redirect()->back()->with(["success" => true, "message" => "Approved."]);
    }
}
