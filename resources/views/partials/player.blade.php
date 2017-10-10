<style>{!!file_get_contents(public_path('css/player.css'))!!}</style>
<div id="jquery_jplayer" style="width: 0; height: 0;"></div>
<div style="" class="sticky_player_container">
    <div id="jp_container" class="sticky_player">
        <div class="buttons">
            <ul>
                <li class="jp-back"><img src="{{asset('public/images/back.svg')}}" alt="Back"></li>
                <li class="jp-play"><img src="{{asset('public/images/play-button.svg')}}" alt="Play"></li>
                <li style="display: none;" class="jp-pause"><img src="{{asset('public/images/pause.svg')}}" alt="Pause">
                </li>
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
                <li class="time_total player_duration"></li>
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
                    <img src="{{asset('public/images/speaker.svg')}}" alt="Mute">
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
    var timeBarWidth = parseInt($('.time_bar').css('width').replace('px', '')),
        my_jPlayer = $("#jquery_jplayer"),
        my_trackName = $("#jp_container .track-name"),
        my_playState = $("#jp_container .play-state"),
        currentPlayingDomElement,
        pageTitle =document.title;

    $(document).ready(function () {
        // Instance jPlayer
        my_jPlayer.jPlayer({
            ready: function () {
                //$(".track-default").click();
            },
            timeupdate: function (event) {
                var percentage = event.jPlayer.status.currentPercentAbsolute,
                    filled = parseInt(percentage * timeBarWidth / 100),
                    currentTime = secondsToTime(parseInt(event.jPlayer.status.currentTime)),
                    durationTime = secondsToTime(parseInt(event.jPlayer.status.duration));
                $('.time_bar_filled').css('width', filled + "px");
                $('.current-time').html(pad(currentTime.m) + ":" + pad(currentTime.s));
                $('.player_duration').html(pad(durationTime.m) + ":" + pad(durationTime.s));
            },
            /* play: function (event) {
             my_playState.text(opt_text_playing);
             },
             pause: function (event) {
             my_playState.text(opt_text_selected);
             },*/
            ended: function (event) {
                // play next if exits
                var nextTrack = $(currentPlayingDomElement).parent().next().find('div')[0];
                if (nextTrack) {
                    playTrack(nextTrack);
                } else {
                    $(".current_track").removeClass('playing');
                    document.title = pageTitle;
                }


            },
            swfPath: "/public/vendors/jplayer/jplayer/jquery.jplayer.swf",
            cssSelectorAncestor: "#jp_container",
            supplied: "mp3",
            wmode: "window",
            volume: 1,
            verticalVolume: true
        });
    });

    function playTrack(track) {
        if ($(track).hasClass('current_track')) {
            if ($(track).hasClass('playing')) {
                my_jPlayer.jPlayer("pause");
                $(track).removeClass('playing');
            } else {
                my_jPlayer.jPlayer("play");
                $(track).addClass('playing');
            }
            return false;
        }

        var name = $(track).find('.song_name')[0];
        my_trackName.text($(name).text());

        my_jPlayer.jPlayer("setMedia", {
            mp3: $(track).data("source")
        });

        my_jPlayer.jPlayer("play");

        document.title = $(name).text();

        $(".current_track").removeClass('current_track playing');
        $(track).addClass('current_track playing');
        currentPlayingDomElement = track;

        var image = $(track).find('.song_poster')[0];
        $(".image_container").css('background-image', $(image).css('background-image'));

        $('.sticky_player_container').addClass('active');
        return false;
    }

    function secondsToTime(secs) {
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
        var s = num + "";
        while (s.length < 2) s = "0" + s;
        return s;
    }
</script>