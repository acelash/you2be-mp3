@extends('layouts.app')

@section('pageTitle', "mandarin-kino")
@section('pageDescription', "Фильмы онлайн без рекламы, в хорошем, hd качестве(720) или даже в fullhd (1080). Все бесплатно и без регистрации")

@section('content')
    <style>{!!file_get_contents(public_path('css/homepage.css'))!!}</style>
    <div class="sticky_player_container">
        <div id="jp_container" class="row sticky_player">
            <div class="col-lg-2 image_container">

            </div>
            <div class="col-lg-2"></div>
            <p>
                <span class="play-state">Track selected</span> :
                <span class="track-name">The Separation</span>
                at <span class="extra-play-info">0%</span>
                of <span class="jp-duration">4 min 29 sec</span>, which is
                <span class="jp-current-time">0 min 0 sec</span>
            </p>
            <ul>
                <li><a class="jp-play" href="#" style="display: inline;">Play</a></li>
                <li><a class="jp-pause" href="#" style="display: none;">Pause</a></li>
                <li><a class="jp-stop" href="#">Stop</a></li>
            </ul>
            <ul>
                <li>volume :</li>
                <li><a class="jp-mute" href="#">Mute</a></li>
                <li><a class="jp-unmute" href="#" style="display: none;">Unmute</a></li>
                <li><a class="jp-volume-bar" href="#">|&lt;----------&gt;|</a></li>
                <li><a class="jp-volume-max" href="#">Max</a></li>
            </ul>
        </div>
    </div>

    <div class="container page_content">
        <div class="row">
            <div class="col-lg-9">
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
            <div class="col-lg-3">
                // popular tags
            </div>
        </div>
        @include("partials.footer")
    </div>

@endsection

@section('footer_scripts')
    <script>
        $(document).ready(function () {

            // Local copy of jQuery selectors, for performance.
            var my_jPlayer = $("#jquery_jplayer"),
                my_trackName = $("#jp_container .track-name"),
                my_playState = $("#jp_container .play-state"),
                my_extraPlayInfo = $("#jp_container .extra-play-info");

            // Some options
            var opt_play_first = false, // If true, will attempt to auto-play the default track on page loads. No effect on mobile devices, like iOS.
                opt_auto_play = true, // If true, when a track is selected, it will auto-play.
                opt_text_playing = "Now playing", // Text when playing
                opt_text_selected = "Track selected"; // Text when not playing

            // A flag to capture the first track
            var first_track = true;

            // Change the time format
            $.jPlayer.timeFormat.padMin = false;
            $.jPlayer.timeFormat.padSec = false;
            $.jPlayer.timeFormat.sepMin = " min ";
            $.jPlayer.timeFormat.sepSec = " sec";

            // Initialize the play state text
            my_playState.text(opt_text_selected);

            // Instance jPlayer
            my_jPlayer.jPlayer({
                ready: function () {
                    $("#jp_container .track-default").click();
                },
                timeupdate: function (event) {
                    my_extraPlayInfo.text(parseInt(event.jPlayer.status.currentPercentAbsolute, 10) + "%");
                },
                play: function (event) {
                    my_playState.text(opt_text_playing);
                },
                pause: function (event) {
                    my_playState.text(opt_text_selected);
                },
                ended: function (event) {
                    my_playState.text(opt_text_selected);
                },
                swfPath: "../dist/jplayer",
                cssSelectorAncestor: "#jp_container",
                supplied: "mp3",
                wmode: "window"
            });

            // Create click handlers for the different tracks
            $(".songs .track").click(function (e) {
                my_trackName.text($(this).text());
                my_jPlayer.jPlayer("setMedia", {
                    mp3: $(this).attr("href")
                });
                if ((opt_play_first && first_track) || (opt_auto_play && !first_track)) {
                    my_jPlayer.jPlayer("play");
                }
                first_track = false;
                $(this).blur();

                var image = $(this).find('img')[0];
                $(".image_container").css('background-image','url("'+image.src+'")');

                return false;
            });
        });
    </script>
@endsection