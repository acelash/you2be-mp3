@extends('admin.layouts.admin')

@section('pageTitle', trans('translate.admin'))


@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Welcome!</h3>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_content">
                        Unckecked comments: {{$unchecked_comments}}<br>
                        Active movies: {{$active_movies}}<br>
                        Unckecked movies: {{$unchecked_movies}}<br>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
