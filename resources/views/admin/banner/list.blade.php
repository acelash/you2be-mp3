{{--/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 13.10.2016
 * Time: 23:58
 */--}}
@extends('admin.layouts.admin')

@section('pageTitle', 'Toate banerele')

@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Toate banerele</h3>
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

                        @foreach($banners as $banner)

                                @if ($loop->first)
                                @else
                                    <div class="ln_solid"></div>
                                @endif


                                <form id="edit_user" data-parsley-validate class="form-horizontal form-label-left" method="POST" action="{{ url('/admin/banners/'.$banner['name']) }}">
                                    <p>{{$banner['title']}}</p>
                                {{ csrf_field() }}
                                <div class="form-group">

                                    <div class="col-md-9 col-sm-9 col-xs-18">
                                        <textarea maxlength="4000" rows="5" name="value" class="form-control">{{ $banner['value'] }}</textarea>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-6">
                                        <button type="submit" class="btn btn-success">Salvează modificările</button>
                                    </div>
                                </div>


                            </form>

                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
