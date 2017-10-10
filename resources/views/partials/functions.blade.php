<script>
    function downloadSong(track,track_id) {
        var event = track.onclick.arguments[0];
        event.stopPropagation();
        storeDownload(track_id)
    }
    function showSong(track) {
        var event = track.onclick.arguments[0];
        event.stopPropagation();
        window.location.href = track.href;
    }

    function storeDownload(track_id) {
        $.ajax({
            type: "GET",
            url: getBaseUrl() + "/song/store_download/" + track_id,
            data: [],
            success: function (response) {
            },
            error: function (request, status, error_message) {
                console.log(request.responseJSON);
            }
        });
    }
</script>