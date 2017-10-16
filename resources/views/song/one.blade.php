<li>
    <div data-song_id="{{$song->id}}" data-source="{{asset($song->file_url)}}" onclick="return playTrack(this)"
       class="track @if($loop->first) track-default @endif ">
        <div class="song_poster" style="background-image: url('{{$song->thumbnail_mini}}')">
            <img class="play" src="{{asset('public/images/play-button_white.png')}}" alt="Play">
            <img class="pause" src="{{asset('public/images/pause_white.png')}}" alt="Pause">
            <div class="song_poster_cover"></div>

        </div>
        <span class="song_name" >{{$song->title}}</span>

        <a class="song_download" onclick="downloadSong(this,{{$song->id}})" download="{{$song->title}} [mp3cloud.su].mp3" {{--target="_blank"--}} href="{{asset($song->file_url)}}">
            <img class="off" src="{{asset('public/images/download.png')}}" alt="Download">
            <img class="on"  src="{{asset('public/images/download2.png')}}" alt="Download">
        </a>
        <a class="song_video" onclick="showSong(this)" href="{{route('show_song_'.$locale,[ 'slug' => prepareSlugUrl($song->id,$song->title)])}}">
            <img class="off" src="{{asset('public/images/video.png')}}" alt="Watch video">
            <img class="on"  src="{{asset('public/images/video2.png')}}" alt="Watch video">
        </a>
        <span class="duration">{{gmdate("i:s", $song->duration)}}</span>
        <div class="inline_share">
            <?php
            $seoTitle = $song->title ." | ".config('app.name');

            $seoDescription = trans("words.share_description_prefix")." ". $seoTitle;//str_limit(strip_tags($entity->text), 160);
            $seoUrl = route('show_song_'.$locale,[ 'slug' => prepareSlugUrl($song->id,$song->title)]);
            $seoImg = $song->thumbnail;
            ?>
            <a onclick="this.onclick.arguments[0].stopPropagation();Share.facebook('{{$seoUrl}}','{{$seoTitle}}','{{$seoImg}}','{{$seoDescription}}')">
                <img alt="facebook" src="{{asset('public/images/facebook.png')}}">
            </a>
            <a onclick="this.onclick.arguments[0].stopPropagation();Share.twitter('{{$seoUrl}}','{{$seoTitle}}')">
                <img alt="twitter" src="{{asset('public/images/twitter.png')}}">
            </a>
            <a onclick="this.onclick.arguments[0].stopPropagation();Share.vkontakte('{{$seoUrl}}','{{$seoTitle}}','{{$seoImg}}','{{$seoDescription}}')">
                <img alt="vk" src="{{asset('public/images/vk.png')}}">
            </a>

            <a onclick="this.onclick.arguments[0].stopPropagation();Share.odnoklassniki('{{$seoUrl}}','{{$seoDescription}}')">
                <img alt="odnoklassniki" src="{{asset('public/images/odnoklassniki-logo.png')}}">
            </a>
        </div>
    </div>
</li>