<li>
    <div data-song_id="{{$song->id}}" data-source="{{asset($song->file_url)}}" onclick="return playTrack(this)"
       class="track @if($loop->first) track-default @endif ">
        <div class="song_poster" style="background-image: url('{{$song->thumbnail_mini}}')">
            <img class="play" src="{{asset('public/images/play-button_white.png')}}" alt="Play">
            <img class="pause" src="{{asset('public/images/pause_white.png')}}" alt="Pause">
            <div class="song_poster_cover"></div>

        </div>
        <span class="song_name" >{{$song->title}}</span>
        <a class="song_download" onclick="downloadSong(this,{{$song->id}})" download="{{$song->title}}.mp3" {{--target="_blank"--}} href="{{asset($song->file_url)}}">
            <img class="off" src="{{asset('public/images/download.png')}}" alt="Download">
            <img class="on"  src="{{asset('public/images/download2.png')}}" alt="Download">
        </a>
        <a class="song_video" onclick="showSong(this)" href="{{route('show_song_'.$locale,[ 'slug' => prepareSlugUrl($song->id,$song->title)])}}">
            <img class="off" src="{{asset('public/images/video.png')}}" alt="Watch video">
            <img class="on"  src="{{asset('public/images/video2.png')}}" alt="Watch video">
        </a>
            <span class="duration">{{gmdate("i:s", $song->duration)}}</span>
    </div>
</li>