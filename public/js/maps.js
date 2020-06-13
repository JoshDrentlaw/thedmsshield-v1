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
    let blue = 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png'
    let blueIcon = new L.Icon({
        iconUrl: blue,
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    })
    let green = 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png'
    let greenIcon = new L.Icon({
        iconUrl: green,
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    })

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
    sidebar.on('closing', function() {
        $(`[src="${green}"]`).attr('src', blue)
    })

    // ADD MARKERS
    let mapMarkers = markers.map((marker, i) => {
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
                $(`[src="${green}"]`).attr('src', blue)
                this.setIcon(greenIcon)
                sidebar.open('marker')
                setMarkerSidebar(marker)
            })
        return {
            marker: mapMarker,
            index: i
        }
    })

    $('.marker-button').on('click', function() {
        sidebar.open('marker')
        let markerId = $(this).data('marker-id')
        let thisMarker = markers.filter(marker => marker.id == markerId)[0]
        let markerIndex = $(this).data('marker-index')
        let thisMapMarker = mapMarkers.filter(marker => marker.index == markerIndex)[0]
        thisMapMarker.marker.setIcon(greenIcon)
        setMarkerSidebar(thisMarker, thisMapMarker.marker)
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

    function setMarkerSidebar(marker, mapMarker=false) {
        if (mapMarker) {
            let markerLatLng = mapMarker.getLatLng()
            markerLatLng = {
                lat: markerLatLng.lat,
                lng: markerLatLng.lng - 100
            }
            map.flyTo(markerLatLng, 0.5, {paddingTopLeft: [10000, 0], duration: 1, easeLinearity: 1})
        }
        $('#marker-id').val(marker.id)
        $('#note-title').text(marker.note_title)
        $('#note-editor').html(marker.note_body)
        tinymce.activeEditor.setContent(marker.note_body)
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
                        setMarkerSidebar(marker)
                    })
                setMarkerSidebar(marker)
            })
    })
})