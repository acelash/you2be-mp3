<?php

namespace App\Http\Controllers;

use App\Models\Movie;

class HomeController extends Controller
{
    public function boot()
    {

    }

    public function index()
    {
        return $this->customResponse("home");
    }
    public function rules()
    {
        return $this->customResponse("rules");
    }
}

