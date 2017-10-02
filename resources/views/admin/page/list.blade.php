{{--/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 13.10.2016
 * Time: 23:58
 */--}}
@extends('admin.layouts.admin')

@section('pageTitle', 'Toate paginile')

@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Toate paginile</h3>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_content">
                        <table id="datatable-users" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Actions</th>
                                <th>Slug</th>
                                <th>Titlu</th>

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
        var is_ajax_loading = false;
        $(document).ready(function () {

            $("#datatable-users").DataTable({
                "autoWidth": false,
                "columns": [
                    {
                        width: 30,
                        render: function (data, type, row) {
                            return renderActionsColumn(data);
                        },
                        sortable: false,
                        bSearchable: false
                    },
                    {"width": "20%"},
                    null
                ],
                "processing": true,
                "serverSide": true,
                "ajax": getBaseUrl() + "admin/pages/datatable",
                "iDisplayLength": 50
            });
        });

        function renderActionsColumn (id) {
            var html = '<a target="_blank" href="'+getBaseUrl()+"admin/pages/"+id+'"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button></a>';
            html += '<a onclick="return confirm(\'This can be undone. Are you sure ?\')" href="'+getBaseUrl()+"admin/pages/delete/"+id+'"><button class="btn btn-warning btn-xs"><i class="fa fa-remove" aria-hidden="true"></i> Remove</button></a>';
            return html;
        };


    </script>

@endsection
