<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 movie_list">
    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 poster_container">
        <a href="{{route('show_movie',[ 'slug' => prepareSlugUrl($movie->id,$movie->title)])}}">
            <img class="img-responsive img-rounded poster" src="{{$movie->thumbnail_medium}}">
        </a>
    </div>
    <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12  movie_info_list">
        <ul>
            <li class="movie_title"><a
                        href="{{route('show_movie',[ 'slug' => prepareSlugUrl($movie->id,$movie->title)])}}">
                    {{$movie->title}}
                    @if($movie->title_original && $movie->title_original !== $movie->title)
                        / {{ $movie->title_original}}
                    @endif
                </a>
                <span class="added">@lang('words.added'): {{ $movie->created_at->format("d.m.Y")}}</span>
            </li>
            <li><span class="info_label">@lang('words.genre'):</span>
                @foreach($movie->genres()->get() as $genre)
                    <a href="{{route('catalog_filtered',['slug'=>'genre','id'=>$genre->id])}}">{{$genre->name}}</a>@if (!$loop->last), @endif
                @endforeach
                <?php $totalVotes = $movie->positive_rating + $movie->negative_rating ?: 1; ?>
                <span class="rating">@lang('words.rating'): <span>{{ round($movie->positive_rating / $totalVotes * 100,2)}}
                        %</span></span>
            </li>
            <li><span class="info_label">@lang('words.year'):</span> <a
                        href="{{route('catalog_filtered',['slug'=>'year','id'=>$movie->year])}}">{{$movie->year}}</a>
            </li>

            <li><span class="info_label">@lang('words.country'):</span>
                @foreach($movie->countries()->get() as $country)
                    <a href="{{route('catalog_filtered',['slug'=>'country','id'=>$country->id])}}">{{$country->name}}</a>@if (!$loop->last), @endif
                @endforeach

            </li>
            <li class="movie_text">{{ str_limit(strip_tags($movie->text),config('constants.MOVIE_TEXT_PREVIEW'))}}</li>
        </ul>

        <a class="watch_btn"
           href="{{route('show_movie',[ 'slug' => prepareSlugUrl($movie->id,$movie->title)])}}"> @lang('words.watch_online') </a>
    </div>


</div>