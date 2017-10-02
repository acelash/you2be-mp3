{{--/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 13.10.2016
 * Time: 23:58
 */--}}
@extends('admin.layouts.admin')

@section('pageTitle', 'Editare activitate ID:' . $entity['id'])

@section('headerScripts', "")

@section('footerScripts', '')

@section('content')

    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Editare activitate ID: {{$entity['id']}}</h3>
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
                              action="{{ url('/admin/genres/'.$entity['id']) }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nume <span
                                            class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="name" required="required" value="{{ old('name') ?: $entity['name']  }}"  class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('job_category_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Categorie <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control col-md-7 col-xs-12" name="job_category_id">
                                        <option value=''>Fara categorie</option>
                                        @foreach ($categories as $option)
                                            <option value="{{$option['id']}}"
                                                    @if($entity['job_category_id'] == $option['id'])selected @endif
                                            >{{$option['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('ord') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ord">Ordine <span
                                            class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input min="0" max="100" type="number" name="ord"  value="{{ old('ord')  ?: $entity['ord'] }}"  class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="ln_solid"></div>

                            <div class="form-group{{ $errors->has('seo_title') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="seo_title">SEO title </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input maxlength="150" type="text" name="seo_title"  value="{{ $entity['seo_title']  }}"  class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('seo_description') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="seo_description">SEO description </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea maxlength="250" name="seo_description" class="form-control col-md-7 col-xs-12">{{$entity['seo_description']  }}</textarea>
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
