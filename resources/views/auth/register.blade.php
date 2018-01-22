@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        {{--<div class="col-md-4 col-md-offset-4 page_content">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('words.register')</div>
                <div class="panel-body">
                    <div class="or_div">@lang('words.register_with')</div>
                    <div class="soc_icons">
                        <a href="{{url('redirect/vkontakte')}}">
                            <img src="{{asset('public/images/vk-social-logotype.png')}}">
                        </a>
                        <a href="{{url('redirect/google')}}">
                            <img src="{{asset('public/images/Google-Plus-icon.png')}}">
                        </a>
                        <a href="{{url('redirect/facebook')}}">
                            <img src="{{asset('public/images/Facebook-icon.png')}}">
                        </a>
                    </div>
                    <div class="or_div">или</div>
                    <form class="form auth" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <input id="name" type="text" class="form-control" name="name"
                                   placeholder="@lang('words.unique_name')"
                                   value="{{ old('name') }}" required autofocus>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <input id="email" type="email" class="form-control" name="email"
                                   placeholder="@lang('words.email')"
                                   value="{{ old('email') }}" required>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <input id="password" type="password" class="form-control" name="password"
                                   placeholder="@lang('words.password')"
                                   required>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation"
                                   placeholder="@lang('words.password_confirm')"
                                   required>
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group submit_container">
                            <button type="submit" class="form_submit_btn">
                                @lang('words.signup')
                            </button>
                            <div class="signup_hint">
                                Уже зарегистрированы? Вам <a href="{{route('login')}}">сюда</a>.
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>--}}
    </div>
</div>
@endsection
