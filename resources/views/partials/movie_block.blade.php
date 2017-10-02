<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 movie">
    <a href="{{route('show_movie',[ 'slug' => prepareSlugUrl($movie->id,$movie->title)])}}">
        <img class="img-responsive img-rounded poster" src="{{$movie->thumbnail_medium}}">
    </a>
    <div class="movie_info">
        <a href="{{route('show_movie',[ 'slug' => prepareSlugUrl($movie->id,$movie->title)])}}"> {{$movie->title}}</a>
        <small>
            <a href="{{route('catalog_filtered',['slug'=>'year','id'=>$movie->year])}}">{{$movie->year}}</a>
        </small>
    </div>

</div>