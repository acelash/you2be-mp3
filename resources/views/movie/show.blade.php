@extends('layouts.app')

<?php
$seoTitle = $entity->title;

if ($entity->title_original && $entity->title_original !== $entity->title)
    $seoTitle .= " (" . $entity->title_original . ")";


$seoDescription = prepareSeoDescription($entity, true);//str_limit(strip_tags($entity->text), 160);
$seoUrl = Request::url();
$seoImg = $entity->thumbnail_medium;
?>
@section('pageTitle', $seoTitle)
@section('pageDescription', $seoDescription)
@section('pageMeta')
    <meta property="og:url" content="{{$seoUrl}}"/>
    <meta property="og:locale" content="RU_ru"/>
    <meta property="og:type" content="article"/>
    <meta property="og:title" content="{{$seoTitle}}"/>
    <meta property="og:description" content="{{$seoDescription}}"/>
    <meta property="og:image" content="{{$seoImg}}"/>
@endsection
@section('content')
    <style>{!!file_get_contents(public_path('css/fullstory.css'))!!}</style>
    <div class="container page_content">
        <div class="row">
            <ul class="breadcrumb">
                <li><a class="accent-color-text" href="{{url('/movies')}}">@lang('words.movies')</a></li>
                <li class="active secondary-text-color">{{ $entity->title}}</li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-7 fullstory_title ">
                <h1>{{ $entity->title}} ({{ $entity->year}})</h1>
            </div>
            <div class="col-md-5 fullstory_ratings">
                <div class="rating_count">
                    <div class="likes"><img alt="нравится" src="{{asset('public/images/thumbs-up.png')}}">
                        <span>{{$entity->positive_rating}}</span> - нравится
                    </div>
                    <div class="unlikes"><img alt="не нравится" src="{{asset('public/images/thumb-down.png')}}">
                        <span>{{$entity->negative_rating}}</span> - не нравится
                    </div>
                </div>
                <div class="rating_bar_container">
                    <div class="rating_bar">
                        <?php
                        $totalVotes = $entity->positive_rating + $entity->negative_rating ?: 1;
                        $positiveProcent = round($entity->positive_rating / $totalVotes * 100, 2);
                        $filled = ceil($positiveProcent) * 3;
                        ?>
                        <div style="width: {{$filled}}px;" class="rating_bar_filled"></div>
                    </div>
                    <div class="procent_container">{{$positiveProcent}}%</div>
                </div>
            </div>
            <hr class="fullstory_header_line divider-color">
        </div>

        <div class="row">
            <div class="col-lg-10 col-md-9 col-sm-12 col-xs-12 content_center">
                <div class="row fullstory">
                    <div class="col-lg-3 col-md-4 col-sm-5 col-xs-12">
                        <img alt="{{ $entity->title}} poster" class="img-responsive img-rounded  poster"
                             src="{{$entity->thumbnail_medium}}">
                    </div>
                    <div class="col-lg-9 col-md-8 col-sm-7 col-xs-12">
                        @if($entity->title_original && $entity->title_original !== $entity->title)
                            <h3 class="original_title">{{ $entity->title_original}}</h3>
                        @endif
                        <ul class="fullstory_info">
                            <li><span class="fullstory_info_label">@lang('words.genre')
                                    :</span> {{$entity->genres()->get()->implode('name',', ')}}</li>
                            <li><span class="fullstory_info_label">@lang('words.country')
                                    :</span> {{$entity->countries()->get()->implode('name',', ')}}</li>
                            <li class="fullstory_text">{!! $entity->text !!}</li>
                            <li class="fullstory_desc">{!! prepareSeoDescription($entity)!!}</li>
                        </ul>

                        <div class="movie_actions">
                            <a href="{{route('toggle_likes',['id'=>$entity->id,'type'=>1])}}">
                                <button id="likeBtn" class="btn like @if($liked)active @endif">
                                    <img alt="нравится" src="{{asset('public/images/thumbs-up_w.png')}}">
                                    Нравиться
                                </button>
                            </a>
                            <a href="{{route('toggle_likes',['id'=>$entity->id,'type'=>2])}}">
                                <button class="btn dislike @if($disliked)active @endif">
                                    <img alt="не нравится" id="dislikeBtn"
                                         src="{{asset('public/images/thumb-down_w.png')}}">
                                </button>
                            </a>
                            <a href="{{route('toggle_watch_later',['id'=>$entity->id])}}">
                                <button id="watchLaterBtn" onclick="watchLater()"
                                        class="btn watch_later @if($watch_later)active @endif">
                                    <img alt="Хочу посмотреть" src="{{asset('public/images/time.png')}}"> Хочу
                                    посмотреть
                                </button>
                            </a>
                            <a href="{{route('toggle_seen',['id'=>$entity->id])}}">
                                <button id="seenBtn" onclick="setSeen()" class="btn seen @if($seen)active @endif">
                                    <img alt="Смотрел" src="{{asset('public/images/correct-symbol.png')}}"> Смотрел
                                </button>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 content_block">
                        <div><h2>@lang('words.watch_online')</h2>
                            <hr class="divider-color">
                        </div>

                        <div class="embeded_video_container">
                            <iframe src="https://www.youtube-nocookie.com/embed/{{$entity->source_id}}?rel=0&amp;showinfo=0&amp;iv_load_policy=3&amp;t={{$entity->source_start_at}}s&amp;"
                                    frameborder="0" allowfullscreen></iframe>
                        </div>
                        <div class="share_container">
                            <span>Понравился фильм?<br> Поделись с друзьями!</span>
                            <a onclick="Share.vkontakte('{{$seoUrl}}','{{$seoTitle}}','{{$seoImg}}','{{$seoDescription}}')">
                                <img alt="vk" src="{{asset('public/images/vk.png')}}">
                            </a>
                            <a onclick="Share.facebook('{{$seoUrl}}','{{$seoTitle}}','{{$seoImg}}','{{$seoDescription}}')">
                                <img alt="facebook" src="{{asset('public/images/facebook.png')}}">
                            </a>
                            <a onclick="Share.odnoklassniki('{{$seoUrl}}','{{$seoDescription}}')">
                                <img alt="odnoklassniki" src="{{asset('public/images/odnoklassniki-logo.png')}}">
                            </a>
                            <a onclick="Share.twitter('{{$seoUrl}}','{{$seoTitle}}')">
                                <img alt="twitter" src="{{asset('public/images/twitter.png')}}">
                            </a>
                        </div>
                    </div>

                </div>

                {{--similar videos--}}
                <div class="content_block">
                    <div><h2>@lang('words.similar_movies')</h2>
                        <hr class="divider-color">
                    </div>
                    <div class="row similar_movies">
                        @foreach($similar_movies as $movie)
                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 similar_movie">
                                <a href="{{route('show_movie',[ 'slug' => prepareSlugUrl($movie->id,$movie->title)])}}">
                                    <img alt="{{ $movie->title}} poster" class="img-responsive img-rounded poster"
                                         src="{{$movie->thumbnail_medium}}">
                                </a>
                                <div class="movie_info">
                                    <a href="{{route('show_movie',[ 'slug' => prepareSlugUrl($movie->id,$movie->title)])}}"> {{$movie->title}}</a>
                                    <small>
                                        <a href="{{route('catalog_filtered',['slug'=>'year','id'=>$movie->year])}}">{{$movie->year}}</a>
                                    </small>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- comments --}}
                <div class="content_block">
                    <div class="block_title"><h2>@lang('words.comments')</h2>
                        <hr class="divider-color">
                        <div>Отзывов: {{$comments->count()}}</div>
                    </div>
                    <div class="row">
                        @forelse($comments as $comment)

                            @if (!$loop->first)
                                <div class="col-lg-12">
                                    <hr>
                                </div>
                            @endif

                            @include('comment.single',['comment'=>$comment])
                        @empty
                            <div class="col-lg-12">Ваш отзыв будет первым!</div>
                        @endforelse
                    </div>
                    @if(auth()->check())
                        @include('comment.add',['movie_id'=>$entity->id])
                    @else
                        <div class="col-lg-12 alert alert-info">
                            Для добавления отзывов, необходимо <a href="{{route('register')}}">зарегистрироваться</a> и
                            <a href="{{route('login')}}">войти</a> на сайт.
                        </div>
                    @endif
                </div>

            </div>

            @include("partials.now_watching",['now_watching'=> $now_watching])
        </div>
        @include("partials.footer")
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // views store
            setTimeout(function () {
                $.ajax({
                    type: "GET",
                    url: getBaseUrl() + "/movie/store_view/{{$entity->id}}",
                    data: [],
                    success: function (response) {
                    },
                    error: function (request, status, error_message) {
                        var response = request.responseJSON;
                    }
                });
            },{{config("constants.STORE_VIEW_AFTER")}});
        });
    </script>
    <script>{!!file_get_contents(public_path('js/share.js'))!!}</script>

@endsection
