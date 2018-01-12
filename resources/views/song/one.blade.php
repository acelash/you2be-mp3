<li>
    <div  class="track">
        <div class="song_poster" style="background-image: url('{{$song->thumbnail_mini}}')"></div>
        <a href="{{route('show_song_'.$locale,[ 'slug' => prepareSlugUrl($song->id,$song->title)])}}" class="song_name" >{{$song->title}}</a>

        <a class="song_download" onclick="downloadSong(this, {{$song->id}},true)">
            <img class="image_buton" src="{{asset('public/images/download.png')}}" alt="Download">
            <img class="wait" src="{{asset('public/images/spinner.gif')}}" alt="Please wait...">
        </a>
        <a class="song_video" href="{{route('show_song_'.$locale,[ 'slug' => prepareSlugUrl($song->id,$song->title)])}}">
            <img  class="image_buton" src="{{asset('public/images/video.png')}}" alt="Watch video">
        </a>
        <span class="duration">{{gmdate("i:s", $song->duration)}}</span>
        <div class="inline_share">
            <?php
            $seoTitle = $song->title ." | ".config('app.name');

            $seoDescription = trans("words.share_description_prefix")." ". $seoTitle;//str_limit(strip_tags($entity->text), 160);
            $seoUrl = route('show_song_'.$locale,[ 'slug' => prepareSlugUrl($song->id,$song->title)]);
            $seoImg = $song->thumbnail;
            ?>
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
    </div>
</li>