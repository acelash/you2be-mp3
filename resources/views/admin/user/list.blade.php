{{--/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 13.10.2016
 * Time: 23:58
 */--}}
@extends('admin.layouts.admin')

@section('pageTitle', trans('admin.all_users'))

@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>{{trans('admin.all_users')}}</h3>
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
                                <th>Actiuni</th>
                                <th>Avatar</th>
                                <th>Nume</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Data creare</th>
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
                {
                    width: "50px",
                    className: "text-centered",
                    render: function (data, type, row) {
                        return '<a target="_blank" href="'+data+'"><img src="'+data+'" class="mini-avatar-preview"></a>';
                    }
                },
                null,
                {"width": "25%"},
                {
                    width: "30px",
                    render: function (data, type, row) {
                        if (data == 1)
                            return 'Admin';
                        else
                            return 'User';
                    }
                },
                {"width": "110px"}
            ],
            "createdRow": function (row, data, index) {
                var css_class = "is_user";
                if (data[4] == 1) {
                    css_class = 'is_admin';
                }
                $('td', row).eq(4).addClass(css_class);
            },
            "processing": true,
            "serverSide": true,
            "ajax": getBaseUrl() + "/admin/users/datatable",
            "iDisplayLength": 50
        });
    });

    function renderActionsColumn (id) {
        var html = '<a target="_blank" href="'+getBaseUrl()+"/admin/users/"+id+'"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button></a>';
        html += '<a onclick="return confirm(\'This can be undone. Are you sure ?\')" href="'+getBaseUrl()+"/admin/users/delete/"+id+'"><button class="btn btn-warning btn-xs"><i class="fa fa-remove" aria-hidden="true"></i> Remove</button></a>';
        return html;
    };
</script>
@endsection
