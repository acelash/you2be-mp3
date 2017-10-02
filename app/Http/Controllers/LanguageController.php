<?php

namespace App\Http\Controllers;

class LanguageController extends Controller
{


    public function boot()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
       // return view('home');
    }

    public function switchLang($locale)
    {

        if ($locale && in_array($locale, config('app.locales')) && session('lang') !== $locale) {
            session(['lang' => $locale]);
        }
        return redirect()->back();
    }

}
