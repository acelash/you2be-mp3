{{--/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 13.10.2016
 * Time: 23:58
 */--}}
@extends('admin.layouts.admin')

@section('pageTitle', 'User ID:' . $user['id'])

@section('content')
    <?php
    $ban_options = [
        ['label' => "1 second", "value" => "1"],
        ['label' => "1 hour", "value" => "3600"],
        ['label' => "1 day", "value" => "3600*24"],
        ['label' => "1 week", "value" => "3600*24*7"],
        ['label' => "1 month", "value" => "3600*24*30"],
        ['label' => "3 months", "value" => "3600*24*90"],
        ['label' => "1 year", "value" => "3600*24*365"]
    ];

    ?>

    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Editing User ID: {{$user['id']}}</h3>
            </div>

        </div>

        <div class="clearfix"></div>

        <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_content">
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


                        @if (count($errors) > 0)
                            <div class="alert-error">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="edit_user" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data" method="POST"
                              action="{{ url('/admin/users/'.$user['id']) }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('avatar') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Avatar</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <img class="mini-avatar-preview" src="{{$user['avatar']}}">
                                    <br><br>
                                    <input type="file" name="avatar">
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span
                                            class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="name" name="name" required="required"
                                           value="{{ old('name') ?: $user['name']  }}"
                                           class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span
                                            class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="email" name="email" required="required"
                                           value="{{ old('email') ?: $user['email']  }}"
                                           class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Roles <span
                                            class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    @foreach ($roles as $role)
                                        <div class="checkbox">
                                            <label class="">
                                                <div class="icheckbox_flat-green checked">
                                                    <input name="roles[]" value="{{$role['id']}}" type="checkbox" class="flat"
                                                           @if($role['checked']) checked="checked" @endif>
                                                </div> {{$role['name']}}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Block user
                                    <br>
                                    <small>New block will override the previous</small></label>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    @if($user['banned_till'])
                                        @if(intval($user['banned_till']) <= time())
                                        <div class="userblock expired">Last block ended on {{date("d.m.Y  H:i",$user['banned_till'])}}</div>
                                        @else
                                        <div class="userblock active"> Blocked till {{date("d.m.Y H:i",$user['banned_till'])}}</div>
                                        @endif
                                    @endif
                                    <select class="form-control col-md-7 col-xs-12" name="ban">
                                        <option>Choose option</option>
                                        @foreach ($ban_options as $option)
                                            <option value="{{$option['value']}}">{{$option['label']}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--<script src="{{url('')}}/public/admin/js/users.js"></script>--}}

@endsection
