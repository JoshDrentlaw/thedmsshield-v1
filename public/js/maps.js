$(document).ready(function() {
    const bounds = [[0,0], [mapHeight, mapWidth]]
    const map = L.map('map-container', {
        crs: L.CRS.Simple,
        keepInView: true,
        minZoom: -1
    })
    map.setZoom(5)
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

    let popup, saveTimeout, editor

    sidebar = L.control.sidebar({
            autopan: true,
            closeButton: true,
            container: 'map-sidebar',
            position: 'left'
        }).addTo(map)
    sidebar.on('closing', function(e) {
        this.disablePanel('marker')
        $(`[src="${green}"]`).attr('src', blue)
    })
    sidebar.disablePanel('marker')

    // ADD MARKERS
    let mapMarkers = markers.map((marker, i) => {
        marker['index'] = i
        let mapMarker = L
            .marker([marker.top, marker.left], {draggable: true, icon: blueIcon})
            .addTo(map)
            .on('dragend', function(e) {
                axios.put(`/markers/${marker.id}`, {type: 'movement', top: e.target._latlng.lat, left: e.target._latlng.lng})
                    .then(res => {
                        if (res.status === 200) {
                            pnotify.success({title: 'Map marker position updated!'})
                        }
                    })
            })
            .on('click', function() {
                $(`[src="${green}"]`).attr('src', blue)
                this.setIcon(greenIcon)
                sidebar.enablePanel('marker')
                sidebar.open('marker')
                setMarkerSidebar(marker)
            })
        return {
            marker: mapMarker,
            index: i
        }
    })

    $('.marker-button').on('click', function() {
        sidebar.enablePanel('marker')
        sidebar.open('marker')
        let markerId = $(this).data('marker-id')
        let thisMarker = markers.filter(marker => marker.id == markerId)[0]
        let markerIndex = $(this).data('marker-index')
        let thisMapMarker = mapMarkers.filter(marker => marker.index == markerIndex)[0]
        let otherMapMarkers = mapMarkers.filter(marker => marker.index != markerIndex)
        thisMapMarker.marker.setIcon(greenIcon)
        otherMapMarkers.map(marker => marker.marker.setIcon(blueIcon))
        setMarkerSidebar(thisMarker, thisMapMarker.marker)
    })

    function setMarkerSidebar(marker, mapMarker=false) {
        if (mapMarker) {
            let markerLatLng = mapMarker.getLatLng()
            markerLatLng = {
                lat: markerLatLng.lat,
                lng: markerLatLng.lng - 100
            }
            map.flyTo(markerLatLng, 0.5, {duration: 1, easeLinearity: 1})
        }
        $('#marker-id').val(marker.id)
        $('#marker-index').val(marker.index)
        $('#place-name').text(marker.place.name)
        $('#body-editor, #body-display').html(marker.place.body)
    }

    $('#body-display').on('click', function () {
        let marker = markers[$('#marker-index').val()]
        $(this).addClass('d-none')
        $('#editor-container').removeClass('d-none')
        editor = tinymceInit(marker.place.id, 'places', {selector: '#body-editor'})
        let iana = luxon.local().toFormat('z')
        $('#save-time').text(luxon.fromISO(marker.place.updated_at).setZone(iana).toFormat('FF'))
    })

    $('#change-view-btn').on('click', function () {
        let body = tinymce.activeEditor.getContent()
        tinymce.activeEditor.destroy()
        $('#editor-container').addClass('d-none')
        $('#body-display').removeClass('d-none')
        $('#body-display').html(body)
    })

    $('.compendium-item').on('click', '.to-marker-btn', function (e) {
        e.preventDefault()
        console.log('to marker')
    })

    $('#new-marker').on('click', function() {
        axios.post('/markers', {map_id, top: mapHeight/2, left: mapWidth/2, campaign_id})
            .then(res => {
                let marker = res.data.marker
                let place = res.data.place
                marker.place = place
                L
                    .marker([mapHeight/2, mapWidth/2], {draggable: true})
                    .addTo(map)
                    .setIcon(greenIcon)
                    .on('dragend', function(e) {
                        axios.put(`/markers/${marker.id}`, {type: 'movement', top: e.target._latlng.lat, left: e.target._latlng.lng})
                            .then(res => {
                                if (res.status === 200) {
                                    //$('.container').append(`<div class="alert alert-success">Map marker position updated!</div>`)
                                }
                            })
                    })
                    .on('click', function() {
                        sidebar.open('marker')
                        setMarkerSidebar(marker)
                    })
                $('#marker-list').append(`<button type="button" class="list-group-item list-group-item-action marker-button" data-marker-index="${mapMarkers.length}" data-marker-id="${marker.id}">${place.name}</button>`)
                mapMarkers.push(marker)
                sidebar.open('marker')
                $('#place-name').focus()
                setMarkerSidebar(marker)
            })
    })

    $('#delete-marker').on('click', function() {
        const id = $('#marker-id').val()
        const index = $('#marker-index').val()
        let thisMapMarker = mapMarkers.filter(marker => marker.index == index)[0]
        axios.delete(`/markers/${id}`)
            .then(res => {
                if (res.status === 200) {
                    sidebar.close()
                    thisMapMarker.marker.removeFrom(map)
                    $('#alert-message').text(res.data.message)
                    $('#ajax-message').addClass(`show ${res.data.class}`).removeClass('invisible')
                    setTimeout(function () {
                        $('#ajax-message').removeClass('show').addClass('fade')
                    }, 3000)
                }
            })
    })
})