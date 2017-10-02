{{--/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 13.10.2016
 * Time: 23:58
 */--}}
@extends("$activeDesign.".'admin.layouts.admin')

@section('pageTitle', trans('translate.moderation'))

@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>{{trans('translate.moderation')}}</h3>
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

                        <table id="datatable-list" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Actions</th>
                                <th>Text</th>
                                <th>Event</th>
                                <th>Author</th>
                                <th>Reports</th>
                                <th>Created at</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{url('')}}/public/admin/js/comments_moderation.js"></script>

@endsection
