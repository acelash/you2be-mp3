<style>{!!file_get_contents(public_path('css/player.min.css'))!!}</style>
<div id="jquery_jplayer" style="width: 0; height: 0;"></div>
<div style="" class="sticky_player_container">
    <div id="jp_container" class="sticky_player">
        <div class="buttons">
            <ul>
                <li onclick="previousSong()" class="jp-back">
                    <img class="off" src="{{asset('public/images/back.png')}}" alt="Back">
                    <img class="on" src="{{asset('public/images/back2.png')}}" alt="Back">
                </li>
                <li class="jp-play">
                    <img class="off" src="{{asset('public/images/play-button.png')}}" alt="Play">
                    <img class="on" src="{{asset('public/images/play-button_orange.png')}}" alt="Play">
                </li>
                <li style="display: none;" class="jp-pause">
                    <img class="off" src="{{asset('public/images/pause.png')}}" alt="Pause">
                    <img class="on" src="{{asset('public/images/pause_orange.png')}}" alt="Pause">
                </li>
                <li onclick="nextSong()" class="jp-next">
                    <img class="off" src="{{asset('public/images/next.png')}}" alt="Next">
                    <img class="on" src="{{asset('public/images/next2.png')}}" alt="Next">
                </li>
                <li onclick="toggleShuffle(this)" class="jp-shuffle">
                    <img class="off" src="{{asset('public/images/shuffle.png')}}" alt="Shuffle">
                    <img class="on" src="{{asset('public/images/shuffle2.png')}}" alt="Shuffle">
                </li>
                <li onclick="toggleLoop(this)" class="jp-replay">
                    <img class="off" src="{{asset('public/images/replay.png')}}" alt="Replay">
                    <img class="on" src="{{asset('public/images/replay2.png')}}" alt="Replay">
                </li>
            </ul>
        </div>
        <div class="timeline_container">
            <ul>
                <li class="time_played current-time"></li>
                <li class="timeline">
                    <div class="time_bar jp-seek-bar">
                        <div class="time_bar_empty"></div>
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
                    <img class="off" src="{{asset('public/images/speaker.png')}}" alt="Mute">
                    <img class="on" src="{{asset('public/images/speaker2.png')}}" alt="Mute">
                </li>
                <li style="display: none" class="volume jp-unmute">
                    <img class="off" src="{{asset('public/images/mute.png')}}" alt="Unmute">
                    <img class="on" src="{{asset('public/images/mute2.png')}}" alt="Unmute">
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
                    <a id="player_download" onclick="downloadCurrentTrack()" download="" target="_blank" href="">
                        <img class="off" src="{{asset('public/images/download.png')}}"
                             alt="Download">
                        <img class="on" src="{{asset('public/images/download2.png')}}"
                             alt="Download">
                    </a>

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
        pageTitle = document.title,
        shuffleMode = false,
        loopMode = false;

    $(document).ready(function () {
        my_jPlayer.jPlayer({
            ready: function () {
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

            ended: function (event) {

                // if loop mode, play again
                if (loopMode) {
                    $(".current_track").removeClass('current_track playing');
                    playTrack(currentPlayingDomElement);
                } else {
                    // id shuffle, play a random song
                    if (shuffleMode) {
                        playRandomSong();
                    } else {
                        // play next if exits
                        var nextTrack = $(currentPlayingDomElement).parent().next().find('div')[0];
                        if (nextTrack) {
                            playTrack(nextTrack);
                        } else {
                            $(".current_track").removeClass('playing');
                            document.title = pageTitle;
                        }
                    }
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

        var name = $(track).find('.song_name')[0],
            name_text = $(name).text();
        my_trackName.text(name_text);

        my_jPlayer.jPlayer("setMedia", {
            mp3: $(track).data("source")
        });

        my_jPlayer.jPlayer("play");

        document.title = name_text;

        $('#player_download').prop('download', name_text + " [mp3cloud.su].mp3");
        $('#player_download').prop('href', $(track).data("source"));

        $(".current_track").removeClass('current_track playing');
        $(track).addClass('current_track playing');
        currentPlayingDomElement = track;

        var image = $(track).find('.song_poster')[0];
        $(".image_container").css('background-image', $(image).css('background-image'));

        $('.sticky_player_container').addClass('active');

        scrollIfNotVisible(track);

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

    function toggleShuffle(btn) {
        if (shuffleMode) {
            $(btn).removeClass('active');
            shuffleMode = false;
        } else {
            $(btn).addClass('active');
            shuffleMode = true;
        }
    }

    function toggleLoop(btn) {
        if (loopMode) {
            $(btn).removeClass('active');
            loopMode = false;
        } else {
            $(btn).addClass('active');
            loopMode = true;
        }
    }

    function downloadCurrentTrack() {
        storeDownload($(currentPlayingDomElement).data('song_id'));
    }

    function playRandomSong() {
        var otherSongs = $(currentPlayingDomElement).parent().siblings();
        $(".current_track").removeClass('current_track playing');
        playTrack($(randomSong(otherSongs)).find('div')[0]);
    }

    function nextSong() {
        if (!currentPlayingDomElement) return false;
        if (shuffleMode) {
            playRandomSong();
        } else {
            var nextTrack = $(currentPlayingDomElement).parent().next().find('div')[0];
            if (nextTrack) {
                playTrack(nextTrack);
            }
        }
    }

    function previousSong() {
        if (!currentPlayingDomElement) return false;
        var previousTrack = $(currentPlayingDomElement).parent().prev().find('div')[0];
        if (previousTrack) {
            playTrack(previousTrack);
        }
    }

    var randomSong = function (obj) {
        var keys = Object.keys(obj);
        keys = keys.filter(function (el) {
            return el.length && el == +el;
        });
        var randomIndex = keys.length * Math.random() << 0;
        return obj[keys[randomIndex]];
    };

    function scrollIfNotVisible(element){
        var offset = $(element).offset().top - $(window).scrollTop();
        if(offset > window.innerHeight){
            $('html,body').animate({scrollTop: offset}, 1000);
            return false;
        } else if(offset < 0){
            $('html,body').animate({scrollTop: $(element).offset().top}, 1000);
            return false;
        }
        return true;
    }
</script>