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
    <meta name="description" content="@yield('pageDescription')" />
    <meta name="keywords" content="смотреть,онлайн,фильмы,бесплатно,без рекламы,в хорошем качестве,полностью" />
    <meta name="yandex-verification" content="f2453e15ef8d6260" />

    @yield('pageMeta')

    <!-- Styles -->
    <style>{!!file_get_contents(public_path('css/bootstrap.min.css'))!!}</style>
    <style>{!!file_get_contents(public_path('css/palette.css'))!!}</style>
    <style>{!!file_get_contents(public_path('css/general.css'))!!}</style>
    {{--<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">--}}
    {{--<link href="{{ asset('public/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/css/palette.css') }}" rel="stylesheet">--}}

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
            <a class="navbar-brand " href="{{url('/')}}">
                <img alt="logo" src="{{asset('public/images/logo-min.png')}}">
                {{config('app.name')}}</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="dropdown multi_ul_dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">@lang('words.all_movies') <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @foreach($genres as $genre)
                            <li>
                                <a href="{{route('catalog_filtered',['slug'=>'genre','id'=>$genre->id])}}">{{$genre->name}}</a>
                            </li>
                        @endforeach
                        <li><a></a></li>
                        <li role="separator" class="divider"></li>
                        <li role="separator" class="divider"></li>
                        <li role="separator" class="divider"></li>
                        <li class="last_in_row"><a href="{{route('catalog_filtered',['slug'=>'year','id'=>date("Y",time())])}}">{{date("Y",time())}}</a></li>
                        <li class="last_in_row"><a href="{{route('catalog_filtered',['slug'=>'year','id'=>date("Y",time())-1])}}">{{date("Y",time())-1}}</a></li>
                        <li class="last_in_row"><a href="#{{route('catalog_filtered',['slug'=>'year','id'=>date("Y",time())-2])}}">{{date("Y",time())-2}}</a></li>
                    </ul>
                </li>
                <li><a href="{{url('movies')}}">@lang('words.new')</a></li>
                <li><a href="{{url('movies?sort=popular')}}">@lang('words.popular')</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if(auth()->guest())
                    <li><a href="{{route('login')}}">@lang('words.login')</a></li>
                    <li><a href="{{route('register')}}">@lang('words.register')</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">{{$user->name}} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            @if($is_admin)
                                <li><a href="{{route('admin_panel')}}">@lang('translate.admin')</a></li>
                            @endif
                                <li><a href="{{route('profile')}}">Мой профиль</a></li>
                                <li><a href="{{route('watch_later')}}">Хочу посмотреть</a></li>
                                <li><a href="{{route('seen')}}">Смотрел</a></li>
                            <li role="separator" class="divider"></li>
                            {{--<li class="dropdown-header">Nav header</li>--}}
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
</body>
</html>
