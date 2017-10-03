<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function boot()
    {

    }

    public function index()
    {

        return $this->customResponse("admin.dashboard");
    }
}
