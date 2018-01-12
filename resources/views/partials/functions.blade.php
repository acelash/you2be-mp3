<script>
    function downloadSong(song, track_id,from_list) {
        if(from_list){
            $(song).find("img").hide();
            $(song).find(".wait").show();
        } else {
            $(song).find(".btn").hide();
            $(song).find("span").show();
        }

        $.ajax({
            type: "GET",
            url: getBaseUrl() + "song/get_link/" + track_id,
            data: [],
            success: function (response) {
                if(from_list){
                    $(song).find("img").show();
                    $(song).find(".wait").hide();
                } else {
                    $(song).find(".btn").show();
                    $(song).find("span").hide();
                }
                if(response.status == "ok"){
                    window.location = getBaseUrl() + "song/get_song/" + track_id;
                } else {
                    alert(response.error);
                }
            },
            error: function (request, status, error_message) {
                if(from_list){
                    $(song).find("img").show();
                    $(song).find(".wait").hide();
                } else {
                    $(song).find(".btn").show();
                    $(song).find("span").hide();
                }
                alert(error_message);
                console.log(request.responseJSON);
            }
        });
    }

</script>