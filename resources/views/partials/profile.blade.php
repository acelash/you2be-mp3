<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4 profile">
    <div class="avatar_container">
        <img class="img-responsive " src="{{asset(auth()->user()->avatar)}}">
    </div>
    <ul class="user_menu">
        <?php $route = Route::currentRouteName(); ?>
        <li @if($route == "profile") class="active" @endif><a href="{{route('profile')}}">Понравившиеся фильмы</a></li>
        <li @if($route == "watch_later") class="active" @endif><a href="{{route('watch_later')}}">Хочу посмотреть</a></li>
        <li @if($route == "seen") class="active" @endif><a href="{{route('seen')}}">Смотрел</a></li>
        <li @if($route == "profile_form") class="active" @endif ><a href="{{route('profile_form')}}">Редактировать профиль</a></li>
        <li @if($route == "password_form") class="active" @endif ><a href="{{route('password_form')}}">Сменить пароль</a></li>
        <li><a href="{{ route('logout') }}"
               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                @lang('words.logout')
            </a>
        </li>
    </ul>

</div>