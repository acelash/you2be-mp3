@extends('layouts.app')

<?php
$seoTitle = auth()->user()->name;
$user = auth()->user();

?>
@section('pageTitle', $seoTitle)
@section('content')
    <style>{!!file_get_contents(public_path('css/profile.css'))!!}</style>
    <div class="container page_content">
        <div class="row">
            @include("partials.profile")
            <div class="col-lg-8 col-md-9 col-sm-9 col-xs-8 content_center">
                <div class="row">
                    <div class="col-md-12">
                        <div class="content_block">
                            <div class="title primary-text-color"><h2>Сменить пароль</h2>
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

                                    <form class="form-horizontal" method="POST" action="{{ route('update_password') }}">
                                        {{ csrf_field() }}

                                        @if($user->has_current_password == 1)
                                            <div class="form-group{{ $errors->has('current_password') ? ' has-error' : '' }}">
                                                <label for="current_password" class="col-md-4 control-label">Текущий
                                                    пароль:</label>
                                                <div class="col-md-6">
                                                    <input id="current_password" type="password" class="form-control"
                                                           name="current_password" required>

                                                    @if ($errors->has('current_password'))
                                                        <span class="help-block">
                                                        <strong>{{ $errors->first('current_password') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <input type="hidden" name="current_password" value="no_current_password">
                                        @endif

                                        <div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
                                            <label for="new_password" class="col-md-4 control-label">Новый
                                                пароль:</label>
                                            <div class="col-md-6">
                                                <input id="new_password" type="password" class="form-control"
                                                       name="new_password" required>

                                                @if ($errors->has('new_password'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('new_password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('new_password_confirmation') ? ' has-error' : '' }}">
                                            <label for="new_password_confirmation" class="col-md-4 control-label">Повторите
                                                новый пароль:</label>
                                            <div class="col-md-6">
                                                <input id="new_password_confirmation" type="password"
                                                       class="form-control" name="new_password_confirmation" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-4">
                                                <button type="submit" class="form_submit_btn">
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
