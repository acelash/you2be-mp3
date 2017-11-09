<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $maxLoginAttempts = 3;
    protected $lockoutTime = 1;
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('doNotCacheResponse');
    }

    public function showLoginForm()
    {
        return $this->customResponse('auth.login');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        if ($request->get('back_url'))
            return redirect($request->get('back_url'));
        else
            return redirect('/');
    }

    protected function sendLoginResponse(Request $request)
    {
        $user = (new User())->find(auth()->user()->id);

        if (intval($user->banned_till) > time()) {
            $request->session()->invalidate();
            return redirect()->back()->with(["success" => false, "message" => trans('translate.is_banned_till')." " . date("d.m.Y H:i", $user->banned_till)]);
        } else {
            $request->session()->regenerate();

            $this->clearLoginAttempts($request);

            return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
        }
    }


}
