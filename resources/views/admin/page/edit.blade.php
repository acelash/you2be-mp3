{{--/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 13.10.2016
 * Time: 23:58
 */--}}
@extends('admin.layouts.admin')

@section('pageTitle', 'Editare pagina ID:' . $entity['id'])

@section('headerScripts', '')

@section('footerScripts', '')

@section('content')

    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Editare pagina ID: {{$entity['id']}}</h3>
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
                              action="{{ url('/admin/pages/'.$entity['id']) }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label class="control-label col-md-1 col-sm-1 col-xs-4" for="name">Titlu <span
                                            class="required">*</span>
                                </label>
                                <div class="col-md-9 col-sm-9 col-xs-18">
                                    <input type="text" name="title" required="required" value="{{ old('title') ?: $entity['title']  }}"  class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
                                <label class="control-label col-md-1 col-sm-1 col-xs-4" for="slug">Slug <span
                                            class="required">*</span>
                                </label>
                                <div class="col-md-9 col-sm-9 col-xs-18">
                                    <input type="text" name="slug" required="required" value="{{ old('slug') ?: $entity['slug']  }}"  class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>


                            <div class="form-group{{ $errors->has('ord') ? ' has-error' : '' }}">
                                <label class="control-label col-md-1 col-sm-1 col-xs-4" for="ord">Continut <span
                                            class="required">*</span>
                                </label>
                                <div class="col-md-9 col-sm-9 col-xs-18">

                                   <textarea rows="20" id="content" name="content" class="form-control">{{ old('content')  ?: $entity['content'] }}</textarea>
                                    -
                                </div>



                            </div>


                            <div class="ln_solid"></div>

                            <div class="form-group{{ $errors->has('seo_title') ? ' has-error' : '' }}">
                                <label class="control-label col-md-1 col-sm-1 col-xs-4" for="seo_title">SEO title </label>
                                <div class="col-md-9 col-sm-9 col-xs-18">
                                    <input maxlength="150" type="text" name="seo_title"  value="{{ $entity['seo_title']  }}"  class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('seo_description') ? ' has-error' : '' }}">
                                <label class="control-label col-md-1 col-sm-1 col-xs-4" for="seo_description">SEO description </label>
                                <div class="col-md-9 col-sm-9 col-xs-18">
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

    <script src="{{url('')}}/public/admin/vendors/tinymce_4.6.4/js/tinymce/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector:'#content',
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table contextmenu paste code'
            ],
            toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code',
        });
    </script>
@endsection
