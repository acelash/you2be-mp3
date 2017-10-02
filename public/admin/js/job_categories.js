var limit_description = 300;
$(document).ready(function () {

    $("#datatable-list").DataTable({
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
            null,
            {"width": "50px"}
        ],
        "processing": true,
        "serverSide": true,
        "ajax": getBaseUrl() + "/admin/job_categories/datatable",
        "iDisplayLength": 50,
        "order": [[ 2,'asc' ]]
    });
});

function renderActionsColumn (id) {
    var html = '<a target="_blank" href="'+getBaseUrl()+"/admin/job_categories/"+id+'"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button></a>';
    html += '<a onclick="return confirm(\'This can be undone. Are you sure ?\')" href="'+getBaseUrl()+"/admin/job_categories/delete/"+id+'"><button class="btn btn-warning btn-xs"><i class="fa fa-remove" aria-hidden="true"></i> Remove</button></a>';
    return html;
};

