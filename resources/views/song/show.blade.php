@extends('layouts.app')

<?php
$seoTitle = $entity->title;

$seoDescription = $seoTitle;//str_limit(strip_tags($entity->text), 160);
$seoUrl = Request::url();
$seoImg = $entity->thumbnail;
?>
@section('pageTitle', $seoTitle)
@section('pageDescription', $seoDescription)
@section('pageMeta')
    <meta property="og:url" content="{{$seoUrl}}"/>
    <meta property="og:locale" content="RU_ru"/>
    <meta property="og:type" content="article"/>
    <meta property="og:title" content="{{$seoTitle}}"/>
    <meta property="og:description" content="{{$seoDescription}}"/>
    <meta property="og:image" content="{{$seoImg}}"/>
@endsection
@section('content')
    <style>{!!file_get_contents(public_path('css/fullstory.css'))!!}</style>
    <div class="container page_content">
        <div class="row">
            <ul class="breadcrumb">
                <li><a class="accent-color-text" href="{{url('/')}}">Songs</a></li>
                <li class="active secondary-text-color">{{ $entity->title}}</li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12 fullstory_title ">
                <h1>{{ $entity->title}}</h1>
            </div>
            <hr class="fullstory_header_line divider-color">
            <div class="col-lg-12 fullstory">
                <div class="share_container">
                    <span>Love this song? Share it with your friends!</span>
                    <a onclick="Share.vkontakte('{{$seoUrl}}','{{$seoTitle}}','{{$seoImg}}','{{$seoDescription}}')">
                        <img alt="vk" src="{{asset('public/images/vk.png')}}">
                    </a>
                    <a onclick="Share.facebook('{{$seoUrl}}','{{$seoTitle}}','{{$seoImg}}','{{$seoDescription}}')">
                        <img alt="facebook" src="{{asset('public/images/facebook.png')}}">
                    </a>
                    <a onclick="Share.odnoklassniki('{{$seoUrl}}','{{$seoDescription}}')">
                        <img alt="odnoklassniki" src="{{asset('public/images/odnoklassniki-logo.png')}}">
                    </a>
                    <a onclick="Share.twitter('{{$seoUrl}}','{{$seoTitle}}')">
                        <img alt="twitter" src="{{asset('public/images/twitter.png')}}">
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-10 col-md-9 col-sm-12 col-xs-12 content_center">
                <div class="row fullstory">
                    <div class="col-lg-3 col-md-4 col-sm-5 col-xs-12">
                        <img alt="{{ $entity->title}} poster" class="img-responsive img-rounded  poster"
                             src="{{$entity->thumbnail}}">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 content_block">
                        <div><h2>@lang('words.watch_videoclip')</h2>
                            <hr class="divider-color">
                        </div>

                        <div class="embeded_video_container">
                            <iframe src="https://www.youtube-nocookie.com/embed/{{$entity->source_id}}?rel=0&amp;showinfo=0&amp;iv_load_policy=3"
                                    frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>

                </div>

                {{-- comments --}}
                <div class="content_block">
                    <div class="block_title"><h2>@lang('words.comments')</h2>
                        <hr class="divider-color">
                        <div>Отзывов: {{$comments->count()}}</div>
                    </div>
                    <div class="row">
                        @forelse($comments as $comment)

                            @if (!$loop->first)
                                <div class="col-lg-12">
                                    <hr>
                                </div>
                            @endif

                            @include('comment.single',['comment'=>$comment])
                        @empty
                            <div class="col-lg-12">Ваш отзыв будет первым!</div>
                        @endforelse
                    </div>
                    @if(auth()->check())
                        @include('comment.add',['movie_id'=>$entity->id])
                    @else
                        <div class="col-lg-12 alert alert-info">
                            Для добавления отзывов, необходимо <a href="{{route('register')}}">зарегистрироваться</a> и
                            <a href="{{route('login')}}">войти</a> на сайт.
                        </div>
                    @endif
                </div>

            </div>
        </div>
        @include("partials.footer")
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // views store
            setTimeout(function () {
                $.ajax({
                    type: "GET",
                    url: getBaseUrl() + "/song/store_view/{{$entity->id}}",
                    data: [],
                    success: function (response) {
                    },
                    error: function (request, status, error_message) {
                        var response = request.responseJSON;
                    }
                });
            },{{config("constants.STORE_VIEW_AFTER")}});
        });
    </script>
    <script>{!!file_get_contents(public_path('js/share.js'))!!}</script>

@endsection
