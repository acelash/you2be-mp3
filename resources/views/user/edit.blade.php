@extends('layouts.app')

<?php
$seoTitle = auth()->user()->name;
$user = auth()->user();
?>
@section('pageTitle', $seoTitle)
@section('footer_scripts')
    <script src="{{ asset('public/js/jquery.maskedinput.min.js') }}"></script>
    <script>
        $(function(){
            $("#birth_date").mask("99.99.9999", {placeholder: "дд.мм.гггг" });
        });
    </script>
@endsection
@section('content')
    <style>{!!file_get_contents(public_path('css/profile.css'))!!}</style>
    <div class="container page_content">
        <div class="row">
            @include("partials.profile")
            <div class="col-lg-8 col-md-9 col-sm-9 col-xs-8 content_center">
                <div class="row">
                    <div class="col-md-12">
                        <div class="content_block">
                            <div class="title primary-text-color"><h2>Редактировать профиль</h2>
                                <hr class="divider-color">
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
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

                                    <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ route('update_user') }}">
                                        {{ csrf_field() }}

                                        <div class="form-group{{ $errors->has('avatar_file') ? ' has-error' : '' }}">
                                            <label for="avatar_file" class="col-md-4 control-label">Аватар:</label>
                                            <div class="col-md-6">

                                                <img class="img-responsive avatar" src="{{$user->avatar}}">

                                                <input id="avatar_file" type="file" class="form-control" name="avatar_file" >

                                                @if ($errors->has('avatar_file'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('avatar_file') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="email" class="col-md-4 control-label">@lang('words.email'):</label>
                                            <div class="col-md-6">
                                                <input id="email" type="email" class="form-control" name="email"  value="{{ $user->email }}" readonly >
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                            <label for="name" class="col-md-4 control-label">Логин (имя пользователя):</label>
                                            <div class="col-md-6">
                                                <input id="name" type="text" class="form-control" name="name"  value="{{ $user->name }}" required >

                                                @if ($errors->has('name'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                                            <label for="firstname" class="col-md-4 control-label">Ваше имя:</label>
                                            <div class="col-md-6">
                                                <input id="firstname" type="text" class="form-control" name="firstname"  value="{{ $user->firstname }}">

                                                @if ($errors->has('firstname'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('firstname') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                                            <label for="lastname" class="col-md-4 control-label">Ваша фамилия:</label>
                                            <div class="col-md-6">
                                                <input id="lastname" type="text" class="form-control" name="lastname"  value="{{ $user->lastname }}">

                                                @if ($errors->has('lastname'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('lastname') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('birt_date') ? ' has-error' : '' }}">
                                            <label for="birt_date" class="col-md-4 control-label">День рождения:</label>
                                            <div class="col-md-6">
                                                <input id="birth_date" type="text" placeholder="дд.мм.гггг" class="form-control" name="birth_date"  value="{{ $user->birth_date ? date("d.m.Y",$user->birth_date) : "" }}">

                                                @if ($errors->has('birt_date'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('birt_date') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('sex') ? ' has-error' : '' }}">
                                            <label for="sex" class="col-md-4 control-label">Пол:</label>
                                            <div class="col-md-6">
                                                <select id="sex" class="form-control" name="sex">
                                                    <option value="0">Не указан</option>
                                                    <option @if($user->sex == 1) selected @endif value="1">Женский</option>
                                                    <option @if($user->sex == 2) selected @endif value="2">Мужской</option>
                                                </select>

                                                @if ($errors->has('sex'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('sex') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-4">
                                                <button  type="submit" class="form_submit_btn">
                                                    Изменить
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            @include("partials.now_watching",['now_watching'=> $now_watching])
        </div>
        @include("partials.footer")
    </div>
@endsection
