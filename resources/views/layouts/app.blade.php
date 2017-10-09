<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    {{--<!-- Global Site Tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-107288343-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments)};
        gtag('js', new Date());
        gtag('config', 'UA-107288343-1');
    </script>--}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('pageTitle') | Вся музыка мира, для тебя</title>
    <link rel="shortcut icon" href="{{asset('public/images/logo-min.png')}}" type="image/x-icon">
    <meta name="description" content="@yield('pageDescription')"/>
    <meta name="keywords" content="смотреть,онлайн,фильмы,бесплатно,без рекламы,в хорошем качестве,полностью"/>
    <meta name="yandex-verification" content="f2453e15ef8d6260"/>

@yield('pageMeta')

<!-- Styles -->
    <style>{!!file_get_contents(public_path('css/bootstrap.min.css'))!!}</style>
    <style>{!!file_get_contents(public_path('css/palette.css'))!!}</style>
    <style>{!!file_get_contents(public_path('css/general.css'))!!}</style>

    <script>
                @if(isset($locales))  var locales = <?=$locales?>;
        @endif
        function getBaseUrl() {
            return "{{url('/')}}";
        }
        function trans(key) {
            var keys = key.split("."),
                file = keys[0],
                label = keys[1];
            if (locales[file] && locales[file][label]) {
                return locales[file][label];
            } else {
                return key;
            }
        }
    </script>
</head>
<body>
<!-- Fixed navbar -->
<nav class="navbar dark-primary-color">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand " href="{{route('home_'.$locale)}}">
                <img alt="logo" src="{{asset('public/images/logo-min.png')}}">
                {{config('app.name')}}</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav" style="padding: 8px">
                <li class="search_container" title="{{$total_songs}} songs">
                    <form method="get" action="{{route('search_'.$locale)}}">
                        <input onkeyup="searchBtnToggle()" type="text" class="form-control search_input" name="q"
                               @if(isset($query))value="{{$query}}" @endif
                               placeholder="Search - enter the name of the song or artist">
                        <button class="search_btn">
                            <img src="{{asset('public/images/search.svg')}}">
                            Search
                        </button>
                    </form>
                </li>

            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{{route('new_'.$locale)}}">@lang('words.new')</a></li>
                <li><a href="{{route('popular_'.$locale)}}">@lang('words.popular')</a></li>
                @if(auth()->check())
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">{{$user->name}} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            @if($is_admin)
                                <li><a href="{{route('admin_panel')}}">@lang('translate.admin')</a></li>
                            @endif
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    @lang('words.logout')
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="back_url" value="{{Request::url()}}">
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

@yield('content')

<!-- Scripts -->
<script src="{{ asset('public/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('public/js/bootstrap.min.js') }}"></script>
{{--<script src="{{ asset('public/js/app.js') }}"></script>--}}
<script type="text/javascript" src="{{asset('public/vendors/jplayer/jplayer/jquery.jplayer.min.js')}}"></script>
@yield('footer_scripts')
<script>
    function searchBtnToggle() {
        if($.trim($('.search_input').val()).length > 1){
            $('.search_btn').prop('disabled',false);
        } else {
            $('.search_btn').prop('disabled',true);
        }
    }

    $(document).ready(function () {
        searchBtnToggle();
    });
</script>
</body>
</html>
