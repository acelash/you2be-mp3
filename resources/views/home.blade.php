@extends('layouts.app')

@section('pageTitle', "mandarin-kino")
@section('pageDescription', "Фильмы онлайн без рекламы, в хорошем, hd качестве(720) или даже в fullhd (1080). Все бесплатно и без регистрации")

@section('content')
    <style>{!!file_get_contents(public_path('css/homepage.css'))!!}</style>
    <div class="container page_content">
        <div class="row">
            <div class="col-md-12">
                @forelse($songs as $song)
                    <p> <audio controls type="audio/mpeg" src="{{asset($song->file_url)}}">Your browser does not support the audio element.</audio> {{$song->title}} </p>
                @empty
                    No songs
                @endforelse
            </div>
        </div>
        @include("partials.footer")
    </div>
@endsection
