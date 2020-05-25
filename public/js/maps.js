$(document).ready(function() {
    const bounds = [[0,0], [mapHeight, mapWidth]]
    const map = L.map('map-container', {
        crs: L.CRS.Simple,
        maxBounds: bounds,
        minZoom: -3
    })
    const image = L.imageOverlay(mapUrl, bounds).addTo(map)
    map.fitBounds(bounds)

    $.get('/markers', function(res) {
        res.map(marker => {
            let mapMarker = L
                .marker([marker.top, marker.left], {draggable: true})
                .addTo(map)
                .bindPopup(`
                    <h5>${marker.note_title}</h5>
                    <textarea>
                        ${marker.note_body}
                    </textarea>
                `)
                .on('click', function(e) {
                    if (!L.popup().isOpen()) {
                        tinymce.init({
                            selector: 'textarea'
                        });
                    }
                })
                .on('dragend', function(e) {
                    axios.put(`/markers/${marker.id}`, {top: e.target._latlng.lat, left: e.target._latlng.lng})
                        .then(function(res) {
                            if (res.status === 200) {
                                //$('.container').append(`<div class="alert alert-success">Map marker position updated!</div>`)
                            }
                        })
                })
        })
    })
})