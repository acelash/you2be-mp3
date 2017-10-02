{{--/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 13.10.2016
 * Time: 23:58
 */--}}
@extends('admin.layouts.admin')

@section('pageTitle', trans('translate.new_entry'))

@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>{{trans('translate.new_entry')}}</h3>
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
                              action="{{ url('/admin/movies) }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('cover') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Cover</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="file" name="avatar">
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Title <span
                                            class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="title" name="title" required="required"
                                           value="{{ old('title')}}"
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
