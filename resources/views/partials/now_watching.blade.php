<style>{!!file_get_contents(public_path('css/now_watching.css'))!!}</style>
<div class="col-lg-2 col-md-3 col-sm-12 col-xs-12 now_watching">
    <div class="title">@lang('words.now_watching'):</div>
    @foreach($now_watching as $movie)
        @if ($loop->first)
            <a href="{{route('show_movie',[ 'slug' => prepareSlugUrl($movie->id,$movie->title)])}}">
                <img alt="{{ $movie->title}} poster" class="img-responsive img-rounded poster" src="{{$movie->thumbnail_medium}}">
            </a>
            <div class="movie_info">
                <a href="{{route('show_movie',[ 'slug' => prepareSlugUrl($movie->id,$movie->title)])}}"> {{$movie->title}}</a>
                <small><a href="{{route('catalog_filtered',['slug'=>'year','id'=>$movie->year])}}">{{$movie->year}}</a></small>
            </div>

        @else
            <div class="now_watching_item no_cover">
                <a href="{{route('show_movie',[ 'slug' => prepareSlugUrl($movie->id,$movie->title)])}}"> {{$movie->title}}
                    ({{$movie->year}})
                </a>

            </div>
        @endif

    @endforeach
    <div class="now_watching_item no_cover">
        <a href="{{route('catalog')}}"> Еще фильмы... >></a>

    </div>
</div>
