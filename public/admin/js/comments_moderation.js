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
            {
                "width": "150px",
                render: function (data, type, row) {
                    var data = data.split("|"),
                        title = data[1],
                        id = data[0];
                    return "<a style='color: blue' target='_blank' href='"+getBaseUrl()+"/admin/events/"+id+"'>"+title+"</a>";
                }
            },
            {
                "width": "75px",
                render: function (data, type, row) {
                    var data = data.split("|"),
                        name = data[1],
                        id = data[0];
                    return "<a style='color: blue' target='_blank' href='"+getBaseUrl()+"/admin/users/"+id+"'>"+name+"</a>";
                }
            },
            {"width": "50px"},
            {"width": "110px"}
        ],

        "processing": true,
        "serverSide": true,
        "ajax": getBaseUrl() + "/admin/comments/datatable_moderation",
        "iDisplayLength": 50,
        "order": [[ 4, "desc" ],[ 5, "desc" ]]
    });
});

function renderActionsColumn (id) {
    var html = '<a target="_blank" href="'+getBaseUrl()+"/admin/moderate_comment/"+id+'"><button class="btn btn-success btn-xs"><i class="fa fa-check" aria-hidden="true"></i> Accept</button></a>';
    html += '<a onclick="return confirm(\'This can be undone. Are you sure ?\')" href="'+getBaseUrl()+"/admin/comments/delete/"+id+'"><button class="btn btn-warning btn-xs"><i class="fa fa-remove" aria-hidden="true"></i> Remove</button></a>';
    return html;
};

