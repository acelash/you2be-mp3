{{--/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 13.10.2016
 * Time: 23:58
 */--}}
@extends('admin.layouts.admin')

@section('pageTitle', 'Songs' )

@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Songs</h3>
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
                                <th>Title</th>
                                <th>Medium thumb</th>
                                <th>Data creare</th>
                                <th>State</th>
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
                    null,
                    {
                        width: "100px",
                        className: "text-centered",
                        render: function (data, type, row) {
                            return '<a target="_blank" href="'+data+'"><img src="'+data+'" class="mini-avatar-preview"></a>';
                        }
                    },
                    {"width": "50px"},
                    {"width": "50px"}
                ],
                "processing": true,
                "serverSide": true,
                "ajax": getBaseUrl() + "admin/songs/datatable",
                "iDisplayLength": 50,
                "order": [[ 4,'desc' ]]
            });
        });

        function renderActionsColumn (id) {
            var html = '<a target="_blank" href="'+getBaseUrl()+"admin/songs/"+id+'"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button></a>';
            html += '<a onclick="return confirm(\'This can be undone. Are you sure ?\')" href="'+getBaseUrl()+"admin/songs/delete/"+id+'"><button class="btn btn-warning btn-xs"><i class="fa fa-remove" aria-hidden="true"></i> Remove</button></a>';
            return html;
        };


    </script>

@endsection
