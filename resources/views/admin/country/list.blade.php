{{--/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 13.10.2016
 * Time: 23:58
 */--}}
@extends('admin.layouts.admin')

@section('pageTitle', trans('translate.countries') )

@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>@lang('translate.countries')</h3> <a class="btn btn-primary" href="{{url('admin/countries/new')}}">@lang('translate.add')</a>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_content">
                        <table id="datatable-list" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Actiuni</th>
                                <th>Nume</th>
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

    <script>
        $(document).ready(function () {

            $("#datatable-list").DataTable({
                "autoWidth": false,
                "columns": [
                    {
                        width: 90,
                        render: function (data, type, row) {
                            return renderActionsColumn(data);
                        },
                        sortable: false,
                        bSearchable: false
                    },
                    null
                ],
                "processing": true,
                "serverSide": true,
                "ajax": getBaseUrl() + "/admin/countries/datatable",
                "iDisplayLength": 50,
                "order": [[ 1,'asc' ]]
            });
        });

        function renderActionsColumn (id) {
            var html = '<a target="_blank" href="'+getBaseUrl()+"/admin/countries/"+id+'"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button></a>';
            html += '<a onclick="return confirm(\'This can be undone. Are you sure ?\')" href="'+getBaseUrl()+"/admin/countries/delete/"+id+'"><button class="btn btn-warning btn-xs"><i class="fa fa-remove" aria-hidden="true"></i> Remove</button></a>';
            return html;
        };


    </script>

@endsection
