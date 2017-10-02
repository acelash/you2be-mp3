@extends('layouts.app')

@section('pageTitle', "mandarin-kino")
@section('pageDescription', "Фильмы онлайн без рекламы, в хорошем, hd качестве(720) или даже в fullhd (1080). Все бесплатно и без регистрации")

@section('content')
    <style>{!!file_get_contents(public_path('css/homepage.css'))!!}</style>
    <div class="container page_content">
        <div class="row">
            <div class="col-md-12">
                <div class="content_block">
                    <div class="title primary-text-color"><h1><a href="{{url('movies?sort=popular')}}">@lang('words.popular_movies')</a></h1><hr class="divider-color"></div>
                    <div class="row">
                        @foreach($popular_movies as $movie)
                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 movie">
                                <a href="{{route('show_movie',[ 'slug' => prepareSlugUrl($movie->id,$movie->title)])}}">
                                    <img alt="{{ $movie->title}} poster" class="img-responsive img-rounded poster" src="{{$movie->thumbnail_medium}}">
                                </a>
                                <div class="movie_info">
                                    <a href="{{route('show_movie',[ 'slug' => prepareSlugUrl($movie->id,$movie->title)])}}"> {{$movie->title}}</a>
                                    <small ><a href="{{route('catalog_filtered',['slug'=>'year','id'=>$movie->year])}}">{{$movie->year}}</a></small>
                                </div>

                            </div>
                        @endforeach
                    </div>
                    <div class="actions">
                        <a href="{{url('movies?sort=popular')}}" class="accent-color">@lang('words.popular_movies')... >></a>
                    </div>
                </div>
                {{--Rating--}}
                <div class="content_block">
                    <div class="title primary-text-color"><h2><a href="{{url('movies?sort=rating')}}">@lang('words.rating_movies')</a></h2><hr class="divider-color"></div>
                    <div class="row">
                        @foreach($rating_movies as $movie)
                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 movie">
                                <a href="{{route('show_movie',[ 'slug' => prepareSlugUrl($movie->id,$movie->title)])}}">
                                    <img alt="{{ $movie->title}} poster" class="img-responsive img-rounded poster" src="{{$movie->thumbnail_medium}}">
                                </a>
                                <div class="movie_info">
                                    <a href="{{route('show_movie',[ 'slug' => prepareSlugUrl($movie->id,$movie->title)])}}"> {{$movie->title}}</a>
                                    <small ><a href="{{route('catalog_filtered',['slug'=>'year','id'=>$movie->year])}}">{{$movie->year}}</a></small>
                                </div>

                            </div>
                        @endforeach
                    </div>
                    <div class="actions">
                        <a href="{{url('movies?sort=rating')}}" class="accent-color">@lang('words.rating_movies')... >></a>
                    </div>
                </div>

                {{--NEW--}}
                <div class="content_block">
                    <?php $years = [date("Y",time())-2,date("Y",time())-1,date("Y",time())]; ?>
                    <div class="title primary-text-color"><h2><a href="{{url('movies/year/'.implode(',',$years))}}">@lang('words.new_movies') ({{date("Y",time())}}-{{date("Y",time())-2}})</a></h2><hr class="divider-color"></div>
                    <div class="row">
                        @foreach($new_movies as $movie)
                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 movie">
                                <a href="{{route('show_movie',[ 'slug' => prepareSlugUrl($movie->id,$movie->title)])}}">
                                    <img alt="{{ $movie->title}} poster" class="img-responsive img-rounded poster" src="{{$movie->thumbnail_medium}}">
                                </a>
                                <div class="movie_info">
                                    <a href="{{route('show_movie',[ 'slug' => prepareSlugUrl($movie->id,$movie->title)])}}"> {{$movie->title}}</a>
                                    <small ><a href="{{route('catalog_filtered',['slug'=>'year','id'=>$movie->year])}}">{{$movie->year}}</a></small>
                                </div>

                            </div>
                        @endforeach
                    </div>
                    <div class="actions">
                        <a href="{{url('movies/year/'.implode(',',$years))}}" class="accent-color">@lang('words.new_movies')... >></a>
                    </div>
                </div>
            </div>
        </div>
        @include("partials.footer")
    </div>
@endsection
