<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <!-- Global Site Tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-108246525-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments)};
        gtag('js', new Date());
        gtag('config', 'UA-108246525-1');
    </script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('pageTitle')</title>
    <link rel="shortcut icon" href="{{asset('public/images/logo.png')}}" type="image/x-icon">
    <meta name="description" content="@yield('pageDescription')"/>
    <meta name="keywords" content="@lang('words.keywords')"/>
    <meta name='wmail-verification' content='429a1142b5e041cc8aa8ba8616c58508' />
@yield('pageMeta')

<!-- Styles -->
    <style>{!!file_get_contents(public_path('css/bootstrap.min.css'))!!}</style>
    <style>{!!file_get_contents(public_path('css/palette.css'))!!}</style>
    <style>{!!file_get_contents(public_path('css/general.css'))!!}</style>

    <script>
                @if(isset($locales))  var locales = <?=$locales?>;
        @endif
        function getBaseUrl() {
            return "{{env('APP_URL')}}";
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

    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "ca-pub-1019858116718047",
            enable_page_level_ads: true
        });
    </script>
</head>
<body>
<!-- Fixed navbar -->
<nav class="navbar">
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
                <img alt="logo" src="{{asset('public/images/logo.png')}}">
                {{config('app.name')}}</a>
        </div>
        <div id="navbar" class="navbar-collapse">
            <ul class="nav navbar-nav" style="padding: 8px">
                <li class="search_container" title="{{$total_songs}} songs">
                    <form method="get" action="{{route('pre_search_'.$locale)}}">
                        <input onkeyup="searchBtnToggle()" type="text" class="form-control search_input" name="q"
                               @if(isset($query))value="{{$query}}" @endif
                               placeholder="@lang('words.search_placeholder')">
                        <button class="search_btn">
                            <img src="{{asset('public/images/search.svg')}}">
                            @lang('words.search')
                        </button>
                    </form>
                </li>

            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{{route('new_'.$locale)}}">@lang('words.new')</a></li>
                <li><a href="{{route('popular_'.$locale)}}">@lang('words.popular')</a></li>
                <li class="languages">
                    <a href="{{route('lang',['slug'=>'en'])}}"><img alt="EN" src="{{asset('public/images/en.png')}}"></a>
                    <a href="{{route('lang',['slug'=>'ru'])}}"><img alt="RU" src="{{asset('public/images/ru.png')}}"></a>
                </li>
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
<script type="text/javascript" src="{{asset('public/vendors/jplayer/jplayer/jquery.jplayer.min.js')}}"></script>
<script>{!!file_get_contents(public_path('js/share.js'))!!}</script>
@yield('footer_scripts')


</body>
</html>
