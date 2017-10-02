@extends('layouts.app')

@section('pageTitle', "Каталог фильмов")
@section('pageDescription', "поиск фильмов")

@section('content')
    <style>{!!file_get_contents(public_path('css/catalog.css'))!!}</style>
    <div class="container page_content">
        <div class="row">
            @include("partials.catalog_filter",['filter'=> $filter])
            <div class="col-lg-8 col-md-9 col-sm-9 col-xs-8 content_center">
                <div class="catalog">
                    <div class="page_title">
                        <h1>Каталог фильмов</h1>
                        @if(count($filter['search']))
                            <div class="tags">
                                Выбранные фильтры:
                                @if(array_key_exists("genres",$filter['search']))
                                    <span id="genres_selected" class="tag_group">жанр:
                                        @foreach($filter['genres'] as $genre)
                                            @if(in_array($genre->id,$filter['search']['genres']))
                                                <span class="light-primary-color" id="genres-{{$genre->id}}">{{$genre->name}}
                                                    <img onclick="removeTag(this)"
                                                         src="{{asset('public/images/cancel.png')}}"></span>
                                            @endif
                                        @endforeach
                                    </span>
                                @endif
                                @if(array_key_exists("countries",$filter['search']))
                                    <span id="countries_selected" class="tag_group">страны:
                                        @foreach($filter['countries'] as $country)
                                            @if(in_array($country->id,$filter['search']['countries']))
                                                <span class="light-primary-color" id="countries-{{$country->id}}">{{$country->name}}
                                                    <img onclick="removeTag(this)"
                                                         src="{{asset('public/images/cancel.png')}}"></span>
                                            @endif
                                        @endforeach
                                    </span>
                                @endif
                                @if(array_key_exists("years",$filter['search']))
                                    <span id="years_selected" class="tag_group">годы:
                                        @foreach($filter['years'] as $year)
                                            @if(in_array($year,$filter['search']['years']))
                                                <span class="light-primary-color" id="years-{{$year}}">{{$year}}
                                                    <img onclick="removeTag(this)"
                                                         src="{{asset('public/images/cancel.png')}}"></span>
                                            @endif
                                        @endforeach
                                    </span>
                                @endif
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-lg-3 total_found">Фильмов: {{$movies->total()}} </div>
                            <div class="col-lg-9 sort_options_container">
                                <ul class="sort_options">
                                    <li onclick="updateSortType('new')"
                                        class="colored @if($sort == 'new')active @endif ">
                                        @lang('words.new')
                                    </li>
                                    <li class="dropdown colored @if(in_array($sort,['popular','popular_1','popular_2','popular_3']))active @endif ">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                           aria-haspopup="true"
                                           aria-expanded="false">@lang('words.popular')

                                            @if($sort == 'popular') ({{strtolower(trans('words.all_time_lower'))}}
                                            ) @endif
                                            @if($sort == 'popular_1') ({{strtolower(trans('words.a_day_lower'))}}
                                            ) @endif
                                            @if($sort == 'popular_2') ({{strtolower(trans('words.a_week_lower'))}}
                                            ) @endif
                                            @if($sort == 'popular_3') ({{strtolower(trans('words.a_month_lower'))}}
                                            ) @endif

                                            <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li class="@if($sort == 'popular_1')active @endif"
                                                onclick="updateSortType('popular_1')">@lang('words.a_day')</li>
                                            <li class="@if($sort == 'popular_2')active @endif"
                                                onclick="updateSortType('popular_2')">@lang('words.a_week')</li>
                                            <li class="@if($sort == 'popular_3')active @endif"
                                                onclick="updateSortType('popular_3')">@lang('words.a_month')</li>
                                            <li class="@if($sort == 'popular')active @endif"
                                                onclick="updateSortType('popular')">@lang('words.all_time')</li>
                                        </ul>
                                    </li>
                                    <li class="dropdown colored @if(in_array($sort,['rating','rating_1','rating_2','rating_3']))active @endif">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                           aria-haspopup="true"
                                           aria-expanded="false">@lang('words.rating')
                                            @if($sort == 'rating') ({{strtolower(trans('words.all_time_lower'))}}
                                            ) @endif
                                            @if($sort == 'rating_1') ({{strtolower(trans('words.a_day_lower'))}}) @endif
                                            @if($sort == 'rating_2') ({{strtolower(trans('words.a_week_lower'))}}
                                            ) @endif
                                            @if($sort == 'rating_3') ({{strtolower(trans('words.a_month_lower'))}}
                                            ) @endif

                                            <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li class="@if($sort == 'rating_1')active @endif"
                                                onclick="updateSortType('rating_1')">@lang('words.a_day')</li>
                                            <li class="@if($sort == 'rating_2')active @endif"
                                                onclick="updateSortType('rating_2')">@lang('words.a_week')</li>
                                            <li class="@if($sort == 'rating_3')active @endif"
                                                onclick="updateSortType('rating_3')">@lang('words.a_month')</li>
                                            <li class="@if($sort == 'rating')active @endif"
                                                onclick="updateSortType('rating')">@lang('words.all_time')</li>
                                        </ul>
                                    </li>
                                    <li class="simple">
                                        @if($view_mode == 2)
                                            <img class="view_mode_icon" onclick="updateViewMode(1)" title="@lang('words.blocks')" src="{{asset('public/images/nine-black-tiles.png')}}">
                                        @else
                                            <img class="view_mode_icon" onclick="updateViewMode(2)" title="@lang('words.inline')" src="{{asset('public/images/list.png')}}">
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <hr class="divider-color">
                    </div>
                </div>

                <div class="row">
                    @foreach($movies as $movie)
                        @if($view_mode == 2)
                            @include('partials.movie_list',['movie'=>$movie])
                        @else
                            @include('partials.movie_block',['movie'=>$movie])
                        @endif
                    @endforeach
                </div>

                {{$movies->links()}}

            </div>

            @include("partials.now_watching",['now_watching'=> $now_watching])
        </div>
        @include("partials.footer")
    </div>
    <script>

        var fields = document.querySelectorAll('.catalog_filter input');

        for (var index = 0; index < fields.length; ++index) {
            var field = fields[index];
            field.addEventListener('change', submitFilterForm);
        }

        function submitFilterForm(skipElements, skipAll) {

            var filterData = {
                'years': [],
                'genres': [],
                'countries': []
            };

            if (!skipAll) {
                checkedBoxes = document.querySelectorAll('.catalog_filter input');

                for (var index = 0; index < checkedBoxes.length; index++) {
                    var field = fields[index];

                    if (!field.checked || (skipElements[field.name] && skipElements[field.name].indexOf(field.value) !== -1)) continue;

                    switch (field.name) {
                        case"genres":
                            filterData.genres.push(parseInt(field.value));
                            break;
                        case"countries":
                            filterData.countries.push(parseInt(field.value));
                            break;
                        case"years":
                            filterData.years.push(parseInt(field.value));
                            break;
                    }
                }
            }

            if (filterData.genres.length || filterData.countries.length || filterData.years.length) {
                document.getElementById("filter_search").value = window.btoa(JSON.stringify(filterData));
                document.getElementById("catalogForm").submit();
            } else {
                document.getElementById("filter_search").value = "";
                document.getElementById("catalogForm").submit();
            }


        }

        function removeTag(tag) {
            var id = tag.parentElement.id,
                id_fragments = id.split('-'),
                element = id_fragments[0],
                element_id = id_fragments[1],
                skipData = {};

            skipData[element] = [];
            skipData[element].push(element_id);
            submitFilterForm(skipData);

        }

        function updateSortType(sortType) {
            document.getElementById("sort_search").value = sortType;
            document.getElementById("catalogForm").submit();
        }
        function updateViewMode(viewMode) {
            document.getElementById("view_search").value = viewMode;
            document.getElementById("catalogForm").submit();
        }
    </script>
@endsection
