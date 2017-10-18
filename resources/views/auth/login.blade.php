@extends('layouts.app')

@section('content')
    <style>{!!file_get_contents(public_path('css/auth.css'))!!}</style>
        <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 page_content">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('words.login')</div>
                    <div class="panel-body">

                        @if (session('message'))
                            <div class=" alert
                                                @if(session('success') == true) alert-success
                                                  @elseif(session('success') == false) alert-error
                                                @endif">
                                <ul>
                                    <li>{{ session('message') }}</li>
                                </ul>
                            </div>
                        @endif

                        <div class="or_div">@lang('words.login_with')</div>
                        <form class="form auth" method="POST" action="{{ route('login') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <input id="email" type="email" class="form-control" name="email"
                                       placeholder="@lang('words.email')"
                                       value="{{ old('email') }}" required autofocus>
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

                            <div class="form-group">
                                <div class="col-md-6 remember_me">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"
                                                   name="remember" {{ old('remember') ? 'checked' : '' }}> @lang('words.remember_me')
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 forgot_password">
                                    <div class="checkbox">
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            @lang('words.forgot_password')
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group submit_container">
                                <button type="submit" class="form_submit_btn">
                                    @lang('words.login')
                                </button>
                               {{-- <div class="signup_hint">
                                    Не зарегистрированы еще? Вам <a href="{{route('register')}}">сюда</a>.
                                </div>--}}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
