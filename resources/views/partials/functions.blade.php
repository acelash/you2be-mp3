<script>
    function downloadSong(song, track_id) {
        $.ajax({
            type: "GET",
            url: getBaseUrl() + "/song/get_link/" + track_id,
            data: [],
            success: function (response) {
                if(response.status == "ok"){
                    window.location = getBaseUrl() + "/song/get_song/" + track_id;
                }
            },
            error: function (request, status, error_message) {
                console.log(request.responseJSON);
            }
        });
    }

</script>