@extends('layouts.app')

@section('content')
    <style>{!!file_get_contents(public_path('css/auth.css'))!!}</style>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 page_content">
                <div class="panel panel-default">
                    <div class="panel-heading">Сброс пароля</div>
                    <div class="panel-body">
                        @if (session('status'))
                            <div class=" alert alert-neutral">
                                <ul>
                                    <li>{{ session('message') }}</li>
                                </ul>
                            </div>
                        @endif
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

                        <form class="form auth" method="POST" action="{{ route('password.request') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div style="margin-top: 15px;" class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <input id="email" type="email" class="form-control" name="email"
                                       placeholder="@lang('words.email')"
                                       value="{{ old('email') }}" required autofocus>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>


                            <div style="margin-top: 15px;" class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <input id="password" type="password" class="form-control" name="password"
                                       placeholder="Новый пароль" required >
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div style="margin-top: 15px;" class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation"
                                       placeholder="Повторите пароль"     required >
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div style="margin-top: 0;" class="form-group submit_container">
                                <button type="submit" class="form_submit_btn">
                                    Сменить пароль
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
