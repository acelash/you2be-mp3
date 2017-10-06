<li>
    <div data-source="{{asset($song->file_url)}}" onclick="return playTrack(this)"
       class="track @if($loop->first) track-default @endif ">
        <div class="song_poster" style="background-image: url('{{$song->thumbnail}}')">
            <img class="play" src="{{asset('public/images/play-button-white.svg')}}" alt="Play">
            <img class="pause" src="{{asset('public/images/pause-white.svg')}}" alt="Pause">
            <div class="song_poster_cover"></div>

        </div>
        <a class="song_name" onclick="showSong(this)" href="{{route('show_song',[ 'slug' => prepareSlugUrl($song->id,$song->title)])}}">{{$song->title}}</a>
        <span class="duration">{{gmdate("i:s", $song->duration)}}</span>
    </div>
</li>