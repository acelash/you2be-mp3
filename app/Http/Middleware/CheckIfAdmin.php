<?php

namespace App\Http\Middleware;

use App\Model\Candidate;
use App\Model\Company;
use App\Models\RoleUser;
use Closure;

class CheckIfAdmin
{

    public function handle($request, Closure $next)
    {


        if (auth()->check()) {

            $isAdmin = (new RoleUser())
                ->where("user_id", auth()->id())
                ->where("role_id", config('constants.ROLE_ADMIN'))
                ->count();

            if ($isAdmin) {
                return $next($request);
            } else {
                return redirect('/');
            }

        } else {
            return $next($request);
        }

    }
}
