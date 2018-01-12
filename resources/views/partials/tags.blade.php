<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="text-align: center;">
    <div class="tags">
        <h2>@lang('words.tags_header')</h2>
        @forelse($tags as $tag)
            <a class="tag"
               href="{{route('show_tag_'.$locale,[ 'slug' => prepareSlugUrl($tag->id,$tag->name)])}}">{{$tag->name}}</a>
        @empty
            <li>No tags</li>
        @endforelse
    </div>
    <div id="fb-root"></div>
    <?php
    if($locale == "ru") $fbLocale = 'RU_ru';
    else $fbLocale = "En_en";
    ?>
    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/{{$fbLocale}}/sdk.js#xfbml=1&version=v2.10&appId=275068006343766";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
    <div style="margin: 0 0 20px;" class="fb-page" data-href="https://www.facebook.com/mp3cloud.su" data-small-header="true"
         data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false">
        <blockquote cite="https://www.facebook.com/mp3cloud.su" class="fb-xfbml-parse-ignore"><a
                    href="https://www.facebook.com/mp3cloud.su">mp3cloud.su</a></blockquote>
    </div>
</div>