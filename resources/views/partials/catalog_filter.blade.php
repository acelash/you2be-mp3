<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4 catalog_filter">
    <div class="title">Фильтры для выбора:</div>

        {{--genres--}}
        <div class="filter_title secondary-text-color">Жанры:</div>
        <div class="funkyradio">
            @foreach($filter['genres'] as $genre)
                <div class="funkyradio-primary">
                    <input type="checkbox" value="{{$genre->id}}" name="genres" id="genre{{$genre->id}}" @if(isChecked($filter['search'],'genres',$genre->id)) checked @endif />
                    <label for="genre{{$genre->id}}">{{$genre->name}}</label>
                </div>
            @endforeach
        </div>

        {{--countries--}}
        <div class="filter_title secondary-text-color">Страны:</div>
        <div class="funkyradio">
            @foreach($filter['countries'] as $country)
                <div class="funkyradio-primary">
                    <input type="checkbox" value="{{$country->id}}" name="countries" id="country{{$country->id}}" @if(isChecked($filter['search'],'countries',$country->id)) checked @endif />
                    <label for="country{{$country->id}}">{{$country->name}}</label>
                </div>
            @endforeach
        </div>

        {{--years--}}
        <div class="filter_title secondary-text-color">Годы:</div>
        <div class="funkyradio">
            @foreach($filter['years'] as $year)
                <div class="funkyradio-primary">
                    <input type="checkbox" value="{{$year}}" name="years" id="year{{$year}}" @if(isChecked($filter['search'],'years',$year)) checked @endif />
                    <label for="year{{$year}}">{{$year}}</label>
                </div>
            @endforeach
        </div>

    <form id="catalogForm" action="{{url("movies")}}" method="get">
        <input id="filter_search" type="hidden" name="filter" @if(isset($_GET['filter'])) value="{{$_GET['filter']}}" @endif >
        <input id="sort_search" type="hidden" name="sort" @if(isset($_GET['sort'])) value="{{$_GET['sort']}}" @endif >
        <input id="view_search" type="hidden" name="view" @if(isset($_GET['view'])) value="{{$_GET['view']}}" @endif >
    </form>
</div>