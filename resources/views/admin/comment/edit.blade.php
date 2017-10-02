{{--/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 13.10.2016
 * Time: 23:58
 */--}}
@extends("$activeDesign.".'admin.layouts.admin')

@section('pageTitle', 'Event ID:' . $entity['id'])

@section('headerScripts',"")

@section('footerScripts', '')

@section('content')

    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Editing Comment ID: {{$entity['id']}}</h3>
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

                        <form id="edit_user" data-parsley-validate class="form-horizontal form-label-left" method="POST"
                              action="{{ url('/admin/comments/'.$entity['id']) }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('text') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="text">Text
                                     <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea rows="6"  class="form-control col-md-7 col-xs-12"  name="text">{{ old('text') ?: $entity['text']  }}</textarea>
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

    {{----}}

@endsection
