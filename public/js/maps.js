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

    let popup,
        editor = tinymce.init({
            selector: '#note-editor',
            height: 500
        }),
        sidebar = L.control.sidebar({
            autopan: true,
            closeButton: true,
            container: 'map-sidebar',
            position: 'left'
        }).addTo(map)
    setMarkerSidebar(1)

    markers.map(marker => {
        let mapMarker = L
            .marker([marker.top, marker.left], {draggable: true})
            .addTo(map)
            .on('dragend', function(e) {
                axios.put(`/markers/${marker.id}`, {type: 'movement', top: e.target._latlng.lat, left: e.target._latlng.lng})
                    .then(res => {
                        if (res.status === 200) {
                            //$('.container').append(`<div class="alert alert-success">Map marker position updated!</div>`)
                        }
                    })
            })
            .on('click', function() {
                console.log(this)
                sidebar.open('marker')
                setMarkerSidebar(marker.id)
            })
    })

    $('.marker-button').on('click', function() {
        sidebar.open('marker')
        let markerId = $(this).data('marker-id')
        setMarkerSidebar(markerId)
    })

    $('#note-title').on('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault()
            let id = $('#marker-id').val()
            let note_title = $(this).text()

            axios.put(`/markers/${id}`, {type: 'note_title', note_title})
                .then(res => {
                    if (res.status === 200) {
                        $('.alert').addClass('show').removeClass('invisible')
                        setTimeout(function () {
                            $('.alert').removeClass('show').addClass('fade')
                        }, 3000)
                    }
                })
        }
    })

    function setMarkerSidebar(markerId) {
        let thisMarker = markers.filter(marker => marker.id == markerId)[0]
        $('#marker-id').val(markerId)
        $('#note-title').text(thisMarker.note_title)
        $('#note-editor').html(thisMarker.note_body)
        tinymce.activeEditor.setContent(thisMarker.note_body)
    }

    $('#note-submit').on('click', function() {
        const $this = $(this)
        const content = tinymce.activeEditor.getContent()
        const id = $('#marker-id').val()
        markers = markers.map(marker => {
            if (marker.id == id) {
                marker.note_body = content
            }
            return marker
        })

        axios.put(`/markers/${id}`, {type: 'note_body', note_body: content})
            .then(res => {
                console.log(res)
                if (res.status === 200) {
                    $('.alert').addClass('show').removeClass('invisible')
                    setTimeout(function () {
                        $('.alert').removeClass('show').addClass('fade')
                    }, 3000)
                }
            })
    })

    $('#new-marker').on('click', function() {
        axios.post('/markers', {map_id, top: mapHeight/2, left: mapWidth/2})
            .then(res => {
                console.log(res)
                L
                    .marker([mapHeight/2, mapWidth/2], {draggable: true})
                    .addTo(map)
                    .on('dragend', function(e) {
                        axios.put(`/markers/${data.id}`, {type: 'movement', top: e.target._latlng.lat, left: e.target._latlng.lng})
                            .then(res => {
                                if (res.status === 200) {
                                    //$('.container').append(`<div class="alert alert-success">Map marker position updated!</div>`)
                                }
                            })
                    })
                    .on('click', function() {
                        console.log(this)
                        sidebar.open('marker')
                        setMarkerSidebar(marker.id)
                    })
            })
    })
})