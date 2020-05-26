$(document).ready(function() {
    const bounds = [[0,0], [mapHeight, mapWidth]]
    const map = L.map('map-container', {
        crs: L.CRS.Simple,
        // maxBounds: bounds,
        minZoom: -3
    })
    const image = L.imageOverlay(mapUrl, bounds).addTo(map)
    map.fitBounds(bounds)

    $.get('/markers', function(res) {
        res.map(marker => {
            let popup = L.popup({ className: 'marker-popup' })
                .setContent(`
                    <input id="marker-id" type="hidden" value="${marker.id}">
                    <h5>${marker.note_title}</h5>
                    <textarea id="note-textarea">
                        ${marker.note_body}
                    </textarea>
                    <button class="mt-3 btn btn-primary note-submit">Submit</button>
                `)
            let mapMarker = L
                .marker([marker.top, marker.left], {draggable: true})
                .addTo(map)
                .bindPopup(popup)
                .on('click', function(e) {
                    if (!L.popup().isOpen()) {
                        tinymce.init({
                            selector: 'textarea#note-textarea',
                            height: 300
                        });
                    }
                })
                .on('dragend', function(e) {
                    axios.put(`/markers/${marker.id}`, {type: 'movement', top: e.target._latlng.lat, left: e.target._latlng.lng})
                        .then(res => {
                            if (res.status === 200) {
                                //$('.container').append(`<div class="alert alert-success">Map marker position updated!</div>`)
                            }
                        })
                })
        })
    })

    $(document).on('click', '.note-submit', function() {
        const content = tinymce.activeEditor.getContent()
        $('#note-textarea').val(content)
        console.log($('#note-textarea').val())
        const id = $('#marker-id').val()
        axios.put(`/markers/${id}`, {type: 'note_body', note_body: content})
            .then(res => {
                console.log(res)
                if (res.status === 200) {
                    tinymce.init({
                        selector: 'textarea#note-textarea',
                        height: 400
                    });
                }
            })
    })
})