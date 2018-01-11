<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>

    @if(env('APP_ENV') == "production")
    <!-- Global Site Tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-108246525-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() {dataLayer.push(arguments)};
            gtag('js', new Date());
            gtag('config', 'UA-108246525-1');
        </script>
    @endif
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="Apl1_LOMZWPa2GnKhYDb2d9xYgXGIZ1hizlhVgE7xG0"/>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('pageTitle')</title>
    <link rel="shortcut icon" href="{{asset('public/images/logo.png')}}" type="image/x-icon">
    <meta name="description" content="@yield('pageDescription')"/>
    <meta name="keywords" content="@lang('words.keywords')"/>
    <meta name='wmail-verification' content='429a1142b5e041cc8aa8ba8616c58508'/>
    <meta name="propeller" content="b34a9b4d77318c3433713099e6cc8e02"/>
@yield('pageMeta')

<!-- Styles -->
    <style>{!!file_get_contents(public_path('css/bootstrap.min.css'))!!}</style>
    <style>{!!file_get_contents(public_path('css/general.css'))!!}</style>
    <script>
        function getBaseUrl() {
            return "{{env('APP_URL')}}";
        }
    </script>
</head>
<body>
@if(env('APP_ENV') == "production") @include('partials.ads') @endif
<!-- Fixed navbar -->
<nav class="navbar">
    <div class="container">
        <div class="navbar-header">
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
                    <a href="{{route('lang',['slug'=>'en'])}}"><img alt="EN"
                                                                    src="{{asset('public/images/en.png')}}"></a>
                    <a href="{{route('lang',['slug'=>'ru'])}}"><img alt="RU"
                                                                    src="{{asset('public/images/ru.png')}}"></a>
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
<script>{!!file_get_contents(public_path('js/share.js'))!!}</script>
@yield('footer_scripts')
</body>
</html>
