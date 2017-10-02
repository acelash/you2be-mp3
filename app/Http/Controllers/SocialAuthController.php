<?php

namespace App\Http\Controllers;

use App\SocialAccountService;
use Socialite;

class SocialAuthController extends Controller
{

    protected $redirectTo = '/';

    public function boot()
    {
        //$this->middleware('auth');
    }

    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(SocialAccountService $service, $provider)
    {
        $error = array_key_exists('error', $_GET) ? $_GET['error'] : false;
        if (!$error) {
            $user = $service->createOrGetUser(Socialite::driver($provider));

            if (intval($user['banned_till']) > time()) {
                return redirect("/login")->with(["success" => false, "message" => trans('translate.is_banned_till')." " . date("d.m.Y H:i", $user['banned_till'])]);
            }

            auth()->login($user);

                return redirect()->to($this->redirectTo);
        } else {
            dd($error);
        }


    }
}
