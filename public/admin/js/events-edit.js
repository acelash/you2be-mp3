var mapBoxToken = 'pk.eyJ1IjoiYWNlc3RhIiwiYSI6ImNpdjUyaDByZjAwMGEydHFtMTcycmF4aHgifQ.w7uFtEs9zulaezaHaHWLAg';

L.mapbox.accessToken = mapBoxToken;
var map = L.mapbox.map('event-edit-map', 'mapbox.outdoors')
    .setView([
        document.getElementById('lat').value,
        document.getElementById('long').value
    ], 15);

var marker = L.marker([document.getElementById('lat').value, document.getElementById('long').value], {
    draggable: true
});
//marker.bindPopup(translate.marker_is_draggable);
marker.on('dragend', function (e) {
    document.getElementById('lat').value = e.target.getLatLng().lat.toString();
    document.getElementById('long').value = e.target.getLatLng().lng.toString();
});
marker.addTo(map);

$(document).ready(function () {});


