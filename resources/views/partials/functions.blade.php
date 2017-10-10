<script>
    function downloadSong(track) {
        var event = track.onclick.arguments[0];
        event.stopPropagation();
    }
    function showSong(track) {
        var event = track.onclick.arguments[0];
        event.stopPropagation();
        window.location.href = track.href;
    }
</script>