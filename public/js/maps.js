$(document).ready(function() {
    const bounds = [[0,0], [mapHeight, mapWidth]]
    const map = L.map('map-container', {
        crs: L.CRS.Simple,
        // maxBounds: bounds,
        minZoom: -3,
        keepInView: true
    })
    const image = L.imageOverlay(mapUrl, bounds).addTo(map)
    map.fitBounds(bounds)

    let quill

    $.get('/markers', function(res) {
        res.map(marker => {
            let popup = L.popup({ className: 'marker-popup' })
                .setContent(`
                    <input id="marker-id" type="hidden" value="${marker.id}">
                    <h5>${marker.note_title}</h5>
                    <div id="note-editor">
                        ${marker.note_body}
                    </div>
                    <button class="mt-3 btn btn-primary note-submit" disabled>Submit</button>
                `)
            let mapMarker = L
                .marker([marker.top, marker.left], {draggable: true})
                .addTo(map)
                .bindPopup(popup)
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

    $(document).on('click', '#note-editor', function(e) {
        console.log(quill)
        if (!quill) {
            $(this).siblings('.note-submit').prop('disabled', false)
            quill = new Quill(e.currentTarget, {
                theme: 'snow'
            })
        }
    })

    $(document).on('click', '.note-submit', function() {
        const $this = $(this)
        const content = quill.getContents()
        const id = $('#marker-id').val()
        $('#note-editor').children().remove().append(content)
        quill.enable(false)

        axios.put(`/markers/${id}`, {type: 'note_body', note_body: content})
            .then(res => {
                console.log(res)
                if (res.status === 200) {
                    $this.prop('disabled', true)
                    quill = {}
                }
            })
    })
})