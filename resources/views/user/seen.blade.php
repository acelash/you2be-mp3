@extends('layouts.app')

<?php
$seoTitle = auth()->user()->name;
?>
@section('pageTitle', $seoTitle)
@section('content')
    <style>{!!file_get_contents(public_path('css/profile.css'))!!}</style>
    <div class="container page_content">
        <div class="row">
            @include("partials.profile")
            <div class="col-lg-8 col-md-9 col-sm-9 col-xs-8 content_center">
                <div class="row">
                    <div class="col-md-12">
                        <div class="content_block">
                            <div class="title primary-text-color"><h2>Посмотрел ({{$seen_movies->total()}})</h2>
                                <hr class="divider-color">
                            </div>
                            <div class="row">
                                @forelse($seen_movies as $movie)
                                    @include('partials.movie_list',['movie'=>$movie])
                                @empty
                                    <div class="col-lg-8 col-lg-offset-2 empty_message">Список пуст. <br>Чтобы добавить сюда фильм который вы уже посмотрели, нажмите кнопку "Смотрел" на странице с фильмом. </div>
                                @endforelse
                            </div>
                            <div class="actions">
                                {{$seen_movies->links()}}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            @include("partials.now_watching",['now_watching'=> $now_watching])
        </div>
        @include("partials.footer")
    </div>
@endsection
