@extends('layouts.app')

<?php
$seoTitle = $entity->title ." | ".trans('words.download_listen');

$seoDescription = trans("words.share_description_prefix")." ". $entity->title;
$seoUrl = Request::url();
$seoImg = $entity->thumbnail;
if($locale == "ru") $metaLocale = 'RU_ru';
else $metaLocale = "En_en";

?>
@section('pageTitle', $seoTitle)
@section('pageDescription', $seoDescription)
@section('pageMeta')
    <meta property="og:url" content="{{$seoUrl}}"/>
    <meta property="og:locale" content="{{$metaLocale}}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:title" content="{{$seoTitle}}"/>
    <meta property="og:description" content="{{$seoDescription}}"/>
    <meta property="og:image" content="{{$seoImg}}"/>
@endsection
@section('content')
    <style>{!!file_get_contents(public_path('css/fullstory.min.css'))!!}</style>
    <style>{!!file_get_contents(public_path('css/homepage.min.css'))!!}</style>
    @include('partials.functions')
    <div class="container page_content">
        <div class="row">
            <div class="col-lg-8 ">
                <h1>{{ $entity->title}}</h1>
                <p>@lang('words.fullstory_text')</p>

                <a class="download_link" onclick="downloadSong(this, {{$entity->id}},false)">
                    <button class="btn">
                        <img class="download" src="{{asset('public/images/download_white.png')}}" alt="download">
                        @lang('words.download_as_mp3')</button>
                    <span style="display: none" class="waiting"><img src="{{asset('public/images/spinner.gif')}}"> @lang('words.preparing_download')</span>
                </a>
                <div class="song_tags">
                    @lang('words.song_tags')
                    @foreach($entity->tags()->take(7)->get() as $tag)
                        <a href="{{route('show_tag_'.$locale,[ 'slug' => prepareSlugUrl($tag->id,$tag->name)])}}"> {{$tag->name}} </a>
                    @endforeach
                </div>
                <div class="share_container">
                    <div>@lang('words.share_text')</div>
                    <a onclick="Share.facebook('{{$seoUrl}}','{{$seoTitle}}','{{$seoImg}}','{{$seoDescription}}')">
                        <img alt="facebook" src="{{asset('public/images/facebook.png')}}">
                    </a>
                    <a onclick="Share.twitter('{{$seoUrl}}','{{$seoTitle}}')">
                        <img alt="twitter" src="{{asset('public/images/twitter.png')}}">
                    </a>
                    <a onclick="Share.vkontakte('{{$seoUrl}}','{{$seoTitle}}','{{$seoImg}}','{{$seoDescription}}')">
                        <img alt="vk" src="{{asset('public/images/vk.png')}}">
                    </a>

                    <a onclick="Share.odnoklassniki('{{$seoUrl}}','{{$seoDescription}}')">
                        <img alt="odnoklassniki" src="{{asset('public/images/odnoklassniki-logo.png')}}">
                    </a>

                </div>
                <div class="content_block">
                    <div><h2>@lang('words.watch_video')</h2>
                        <hr class="divider-color">
                    </div>

                    <div class="embeded_video_container">
                        <iframe src="https://www.youtube-nocookie.com/embed/{{$entity->source_id}}?rel=0&amp;showinfo=0&amp;iv_load_policy=3"
                                frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="content_block">
                    <div><h2>@lang('words.similar_songs')</h2>
                        <hr class="divider-color">
                    </div>

                    <ul class="songs">
                        @forelse($similar as $song)
                            @include('song.one',['song'=>$song])
                        @empty
                            <li>No songs</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            @include('partials.tags',['tags'=> $hot_tags])
        </div>
        @include("partials.footer")
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // views store
            setTimeout(function () {
                $.ajax({
                    type: "GET",
                    url: getBaseUrl() + "song/store_view/{{$entity->id}}",
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
@endsection