@extends('layouts.app')

@section('pageTitle', "mandarin-kino")
@section('pageDescription', "Фильмы онлайн без рекламы, в хорошем, hd качестве(720) или даже в fullhd (1080). Все бесплатно и без регистрации")

@section('content')
    <style>{!!file_get_contents(public_path('css/homepage.css'))!!}</style>
    <div class="container page_content">
        <div class="row">
            <div class="col-md-12">
                <div class="content_block">
                    <div class="title primary-text-color">
                        <h1>О сайте</h1>
                        <hr class="divider-color">
                    </div>
                    <div class="row">
                        <div class="col-lg-12">

                        </div>
                    </div>

                </div>
            </div>
        </div>
        @include("partials.footer")
    </div>
@endsection
