<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfile;
use App\Http\Requests\UserChangePassword;
use App\Models\Movie;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class UserController extends Controller
{
    protected $templateDirectory = 'user';

    public function boot()
    {
        $this->setModel(new User());
    }

    public function profile()
    {
        $viewData = [
            'now_watching' => (new Movie())->getNowWatching()->get(),
            'liked_movies' => (new Movie())->getAll()
                ->ratingsTypes()
                ->join(DB::raw("(
                                SELECT 
                                    MAX(ID) as id,
                                    movie_id
                                FROM 
                                    movie_vote
                                WHERE movie_vote.user_id = " . auth()->id() . "
                                AND type = 1 /* positive */
                                GROUP BY movie_id    
                            ) as liked"), "liked.movie_id", "=", "movies.id")
                ->paginate(config("constants.LIKED_MOVIES_ON_PAGE"))
        ];
        return $this->customResponse("{$this->templateDirectory}.profile", $viewData);
    }

    public function seen()
    {
        $viewData = [
            'now_watching' => (new Movie())->getNowWatching()->get(),
            'seen_movies' => (new Movie())->getAll()
                ->ratingsTypes()
                ->join(DB::raw("(
                                SELECT 
                                    MAX(ID) as id,
                                    movie_id
                                FROM 
                                    movie_seen
                                WHERE user_id = " . auth()->id() . "
                                GROUP BY movie_id    
                            ) as seen"), "seen.movie_id", "=", "movies.id")
                ->paginate(config("constants.LIKED_MOVIES_ON_PAGE"))
        ];
        return $this->customResponse("{$this->templateDirectory}.seen", $viewData);
    }

    public function watchLater()
    {
        $viewData = [
            'now_watching' => (new Movie())->getNowWatching()->get(),
            'watch_later_movies' => (new Movie())->getAll()
                ->ratingsTypes()
                ->join(DB::raw("(
                                SELECT 
                                    MAX(ID) as id,
                                    movie_id
                                FROM 
                                    movie_watch_later
                                WHERE user_id = " . auth()->id() . "
                                GROUP BY movie_id    
                            ) as watch_later"), "watch_later.movie_id", "=", "movies.id")
                ->paginate(config("constants.LIKED_MOVIES_ON_PAGE"))
        ];
        return $this->customResponse("{$this->templateDirectory}.watch_later", $viewData);
    }

    public function showEditForm()
    {
        $viewData = [
            'now_watching' => (new Movie())->getNowWatching()->get()
        ];
        return $this->customResponse("{$this->templateDirectory}.edit", $viewData);
    }

    public function showPasswordForm()
    {
        $viewData = [
            'now_watching' => (new Movie())->getNowWatching()->get()
        ];
        return $this->customResponse("{$this->templateDirectory}.password", $viewData);
    }

    public function updateProfile(StoreProfile $request)
    {
        $input = $request->all();

        $newData = [
            'name' => $input['name'],
            'firstname' => $input['firstname'],
            'lastname' => $input['lastname'],
            'sex' => intval($input['sex']),
            'birth_date' => strtotime($input['birth_date']),
        ];

        if (array_key_exists('avatar_file', $input) && $input['avatar_file']) {
            $validator = Validator::make([
                'avatar_file' => $input['avatar_file']
            ], [
                'avatar_file' => 'image|mimes:jpg,jpeg,gif,png|max:50000'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // salvam imagine medium
            $file = $request->file("avatar_file");
            if ($file && $file->isValid()) {
                $destinationPath =  base_path() .'/'.config("constants.AVATARS_PATH");
                $fileName = auth()->id() . '.' . $file->getClientOriginalExtension();
                if ($file->move($destinationPath, $fileName)) {

                    $pathToImage = $destinationPath.$fileName;

                    // resize image
                    $resize = Image::make($pathToImage);
                    $resize->heighten(config("constants.AVATARS_HEIGHT"));
                    $resize->save($pathToImage);
                    // end resize image
                    //optimize image
                    /*$optimizerChain = OptimizerChainFactory::create();
                    $optimizerChain->optimize($pathToImage);*/

                    $newData['avatar'] = asset(config("constants.AVATARS_PATH") . $fileName);
                }
            }
        }


        $update = $this->getModel()->find(auth()->id());
        DB::beginTransaction();
        try {
            $update->update($newData);

            DB::commit();
            return redirect()->back()->with(["success" => true, "message" => "Изменения успешно сохранены!"]);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(["success" => false, "message" => $e->getMessage()]);
        }

    }

    public function updatePassword(UserChangePassword $request)
    {
        $input = $request->all();
        $user = (new User())->find(auth()->user()->id);

        if($user->has_current_password == 1 && $user->password !== bcrypt($input['current_password']))
            return redirect()->back()->with(["success" => false, "message" => "Неправильный текущий пароль"]);

        $user->password = bcrypt($input['new_password']);
        $user->has_current_password = 1;
        $user->save();

        return redirect()->back()->with(["success" => true, "message" =>"Пароль успешно изменен!"]);
    }
}
