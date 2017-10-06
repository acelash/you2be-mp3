@extends('layouts.app')

@section('pageTitle', "TUNE-TUBE")
@section('pageDescription', "")

@section('content')
    <style>{!!file_get_contents(public_path('css/homepage.css'))!!}</style>
    <div class="container page_content">
        <div class="row">
            <div class="col-lg-8">
                <div id="jquery_jplayer" style="width: 0px; height: 0px;"><img id="jp_poster_0"
                                                                               style="width: 0px; height: 0px; display: none;">
                    <audio id="jp_audio_0" preload="metadata"
                           src="http://www.jplayer.org/audio/mp3/Miaow-05-The-separation.mp3"></audio>
                </div>
                <div class="player_container">
                    <ul class="songs">
                        @forelse($songs as $song)
                            <li><a href="{{asset($song->file_url)}}"
                                   class="track @if($loop->first) track-default @endif ">
                                    <img src="{{$song->thumbnail}}">
                                    {{$song->title}}
                                </a>
                            </li>
                        @empty
                            <li>No songs</li>
                        @endforelse
                    </ul>
                </div>


            </div>
            <div class="col-lg-4">
                // popular tags
            </div>
        </div>
        @include("partials.footer")
    </div>

@endsection

@section('footer_scripts')
    @include('partials.player')
@endsection