<style>{!!file_get_contents(public_path('css/player.css'))!!}</style>
<div class="sticky_player_container">
        <div id="jp_container" class="sticky_player">
            <div class="buttons">
                <ul>
                    <li class="jp-back"><img src="{{asset('public/images/back.svg')}}" alt="Back"></li>
                    <li class="jp-play"><img src="{{asset('public/images/play-button.svg')}}" alt="Play"></li>
                    <li style="display: none;" class="jp-pause"><img src="{{asset('public/images/pause.svg')}}"  alt="Pause"></li>
                    <li class="jp-next"><img src="{{asset('public/images/next.svg')}}" alt="Next"></li>
                    <li class="jp-shuffle"><img src="{{asset('public/images/shuffle.svg')}}" alt="Shuffle"></li>
                    <li class="jp-replay"><img src="{{asset('public/images/replay.svg')}}" alt="Replay"></li>
                    {{--<li class="jp-stop"><img src="{{asset('public/images/stop.svg')}}"  alt="Stop"></li>--}}
                </ul>
            </div>
            <div class="timeline_container">
                <ul>
                    <li class="time_played current-time"></li>
                    <li class="timeline">
                        <div class="time_bar jp-seek-bar">
                            <div class="time_bar_filled jp-play-bar"></div>
                        </div>

                    </li>
                    <li class="time_total duration"></li>
                </ul>
            </div>
            <div class="volume_container">
                <div class="volume_bar_container jp-volume-bar">
                    <div class="volume_bar">
                        <div class="volume_bar_filled jp-volume-bar-value"></div>
                    </div>
                    <div class="arrow-down"></div>
                </div>
                <ul>
                    <li class="volume jp-mute">
                        <img  src="{{asset('public/images/speaker.svg')}}" alt="Mute">
                    </li>
                    <li style="display: none" class="volume jp-unmute">
                        <img src="{{asset('public/images/mute.svg')}}" alt="Unmute">
                    </li>
                </ul>
            </div>
            <div class="track_container">
                <ul>
                    <li class="image_container"></li>
                    <li class="track_title track-name"></li>
                </ul>
            </div>
            <div class="actions_container">
                <ul>
                    <li class="option" title="Download">
                        <img onclick="download()" src="{{asset('public/images/down-arrow.svg')}}" alt="Download">
                    </li>
                </ul>
            </div>
        </div>
</div>
<script>
    $(document).ready(function () {
        var timeBarWidth = parseInt($('.time_bar').css('width').replace('px',''));

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
        $.jPlayer.timeFormat.sepMin = " : ";
        $.jPlayer.timeFormat.sepSec = " ";

        // Initialize the play state text
        my_playState.text(opt_text_selected);

        // Instance jPlayer
        my_jPlayer.jPlayer({
            ready: function () {
                $(".track-default").click();
            },
            timeupdate: function (event) {
                var percentage = event.jPlayer.status.currentPercentAbsolute,
                    filled = parseInt(percentage*timeBarWidth/100),
                    currentTime = secondsToTime(parseInt(event.jPlayer.status.currentTime)),
                    durationTime = secondsToTime(parseInt(event.jPlayer.status.duration));
                $('.time_bar_filled').css('width',filled+"px");
                $('.current-time').html(pad(currentTime.m) +":"+pad(currentTime.s));
                $('.duration').html(pad(durationTime.m) +":"+pad(durationTime.s));
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
            swfPath: "/public/vendors/jplayer/jplayer/jquery.jplayer.swf",
            cssSelectorAncestor: "#jp_container",
            supplied: "mp3",
            wmode: "window",
            volume:1,
            verticalVolume:true
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

    function secondsToTime(secs)
    {
        secs = Math.round(secs);
        var hours = Math.floor(secs / (60 * 60));

        var divisor_for_minutes = secs % (60 * 60);
        var minutes = Math.floor(divisor_for_minutes / 60);

        var divisor_for_seconds = divisor_for_minutes % 60;
        var seconds = Math.round(divisor_for_seconds);

        var obj = {
            "h": hours,
            "m": minutes,
            "s": seconds
        };
        return obj;
    }
    function pad(num) {
        var s = num+"";
        while (s.length < 2) s = "0" + s;
        return s;
    }
</script>