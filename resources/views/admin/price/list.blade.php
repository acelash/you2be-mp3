{{--/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 13.10.2016
 * Time: 23:58
 */--}}
@extends('admin.layouts.admin')

@section('pageTitle', 'Toate preţurile')

@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Preţurile pentru OFERTE</h3>
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

                        @foreach($offers as $offer)

                                @if ($loop->first)
                                @else
                                    <div class="ln_solid"></div>
                                @endif

                                    <p>{{$offer['title']}}:</p>


                                    @foreach($offer['values'] as $price)
                                        <form id="edit_user" data-parsley-validate class="form-horizontal form-label-left" method="POST" action="{{ url('/admin/prices/'.$offer['name']) }}">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="perioada" value="{{$price['perioada']}}">
                                            <div class="form-group">
                                                <div class="col-md-2 col-sm-2 col-xs-4">
                                                    <p class="perioada_text">{{$price['perioada_text']}}</p>
                                                </div>
                                                <div class="col-md-7 col-sm-7 col-xs-15">
                                                    <input type="number" min="0" max="999999" step="0.01" name="value" class="form-control" value="{{ $price['value'] }}">
                                                </div>
                                                <div class="col-md-3 col-sm-3 col-xs-6">
                                                    <button type="submit" class="btn btn-success"><i class="fa  fa-save"></i> Modifică</button>
                                                </div>
                                            </div>


                                        </form>
                                    @endforeach


                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Preţurile pentru CV-uri</h3>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_content">

                        @foreach($cvuri as $cv)

                            @if ($loop->first)
                            @else
                                <div class="ln_solid"></div>
                            @endif

                            <p>{{$cv['title']}}:</p>


                            @foreach($cv['values'] as $price)
                                <form id="edit_user" data-parsley-validate class="form-horizontal form-label-left" method="POST" action="{{ url('/admin/prices/'.$cv['name']) }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="perioada" value="{{$price['perioada']}}">
                                    <div class="form-group">
                                        <div class="col-md-2 col-sm-2 col-xs-4">
                                            <p class="perioada_text">{{$price['perioada_text']}}</p>
                                        </div>
                                        <div class="col-md-7 col-sm-7 col-xs-15">
                                            <input type="number" min="0" max="999999" step="0.01" name="value" class="form-control" value="{{ $price['value'] }}">
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-6">
                                            <button type="submit" class="btn btn-success"><i class="fa  fa-save"></i> Modifică</button>
                                        </div>
                                    </div>


                                </form>
                            @endforeach


                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Pachete de access la CV-uri</h3>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_content">

                        @foreach($cv_packs as $pack)


                                <form id="edit_user" data-parsley-validate class="form-horizontal form-label-left" method="POST" action="{{ url('/admin/prices/cv_pack') }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="perioada" value="{{$pack['perioada']}}">
                                    <div class="form-group">
                                        <div class="col-md-2 col-sm-2 col-xs-4">
                                            <p class="perioada_text">{{$pack['perioada']}} CV-uri</p>
                                        </div>
                                        <div class="col-md-7 col-sm-7 col-xs-15">
                                            <input type="number" min="0" max="999999" step="0.01" name="value" class="form-control" value="{{ $pack['value'] }}">
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-6">
                                            <button type="submit" class="btn btn-success"><i class="fa  fa-save"></i> Modifică</button>
                                        </div>
                                    </div>


                                </form>



                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .perioada_text {
            margin: 8px;
        }
    </style>
@endsection
