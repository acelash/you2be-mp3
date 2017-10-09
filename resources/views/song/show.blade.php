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
            <div class="col-lg-8 ">
                <h1>{{ $entity->title}}</h1>
                <p>@lang('words.fullstory_text')</p>

                <button id="play_song" class="btn listen track" data-source="{{asset($entity->file_url)}}" onclick="return playTrack(this)">
                    <img class="play" src="{{asset('public/images/play-button-white.svg')}}" alt="Play">
                    <img class="pause" src="{{asset('public/images/pause-white.svg')}}" alt="Pause">
                    @lang('words.listen')
                    <div class="song_poster" style="background-image: url('{{$entity->thumbnail}}');display: none">
                        <span style="display: none" class="song_name" >{{$entity->title}}</span>
                    </div>
                </button>

                <button onclick="download({{$entity->id}})" class="btn">
                    <img class="download" src="{{asset('public/images/download.svg')}}" alt="download">
                    @lang('words.download_as_mp3')</button>

                <div class="song_tags">
                    Song tags:
                    @foreach($entity->tags()->get() as $tag)
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
    <script>{!!file_get_contents(public_path('js/share.js'))!!}</script>

@endsection
@section('footer_scripts')
    @include('partials.player-single')
@endsection