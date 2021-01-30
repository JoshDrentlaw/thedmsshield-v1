$(document).ready(function() {
    const bounds = [[0,0], [mapHeight, mapWidth]]
    const map = L.map('map-container', {
        crs: L.CRS.Simple,
        minZoom: -3,
        keepInView: true
    })
    map.setZoom(5)
    const image = L.imageOverlay(mapUrl, bounds).addTo(map)
    map.fitBounds(bounds).setZoom(-1)
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
    let black = 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-black.png'

    let mapMarkers = []

    sidebar = L.control.sidebar({
            autopan: true,
            closeButton: true,
            container: 'map-sidebar',
            position: 'left'
        }).addTo(map)
    sidebar.on('closing', function(e) {
        this.disablePanel('marker')
        $(`[src="${green}"]`).attr('src', blue)
        if ($('.show-place-change-view-btn').is(':visible')) {
            $('.show-place-change-view-btn').trigger('click')
        }
    })
    sidebar.on('content', function (e) {
        if (e.id !== 'marker') {
            if ($('.show-place-change-view-btn').is(':visible')) {
                $('.show-place-change-view-btn').trigger('click')
            }
        }
    })
    sidebar.disablePanel('marker')

    function addMarker(marker) {
        return L
            .marker([marker.top, marker.left], {
                draggable: true,
                icon: blueIcon,
                id: marker.id
            })
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
    }

    // ADD MARKERS
    markers.map(marker => {
        mapMarkers.push(addMarker(marker))
    })

    $(document).on('click', '.marker-list-button', function() {
        let markerId = $(this).data('marker-id')
        axios.get(`/markers/${markerId}`)
            .then(res => {
                let marker = res.data
                sidebar.enablePanel('marker')
                sidebar.open('marker')
                mapMarkers.map(mapMarker => {
                    if (mapMarker.options.id == marker.id) {
                        mapMarker.setIcon(greenIcon)
                        setMarkerSidebar(marker, mapMarker)
                    } else {
                        mapMarker.setIcon(blueIcon)
                    }
                })
            })
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
        $('#place-id').val(marker.place.id)
        $('.save-time').text(marker.place.updated_at)
        $('.show-place-name').text(marker.place.name)
        $('.show-place-body-editor, .show-place-body-display').html(marker.place.body)
    }

    /* $('.show-place-body-display').on('click', function () {
        let marker = markers[$('#marker-index').val()]
        $(this).addClass('d-none')
        $('#editor-container').removeClass('d-none')
        editor = tinymceInit(marker.place.id, 'places', {selector: '.show-place-body-editor'})
        let iana = luxon.local().toFormat('z')
        $('#save-time').text(luxon.fromISO(marker.place.updated_at).setZone(iana).toFormat('FF'))
    })

    $('.show-place-change-view-btn').on('click', function () {
        let body = tinymce.activeEditor.getContent()
        tinymce.activeEditor.destroy()
        $('#editor-container').addClass('d-none')
        $('.show-place-body-display').removeClass('d-none')
        $('.show-place-body-display').html(body)
    }) */

    function addMapMarker(e, placeId = false) {
        sidebar.close()
        $('#map-container').append(`<img id="new-map-marker" data-place-id="${placeId}" src="${black}" alt="Black map marker icon">`)
        $('#new-map-marker').css({
            position: 'fixed',
            top: e.offsetX + 12.5,
            left: e.offsetY - 12,
            zIndex: '5000'
        })
        $('#map-container').css('cursor', `pointer`)
        map.on('mousemove', newMarkerMouseMove)
        map.on('click', newMarkerClick)
        map.on('contextmenu', newMarkerCancel)
    }

    function newMarkerMouseMove(e) {
        $('#new-map-marker').css({
            top: e.originalEvent.offsetY + 12.5,
            left: e.originalEvent.offsetX - 12
        })
    }

    function newMarkerClick(e) {
        turnOffNewMarkerEvents()
        let placeId = $('#new-map-marker').data('place-id')
        $('#new-map-marker').remove()
        $('#map-container').css('cursor', `grab`)
        let name = randomWords({
            exactly: 1,
            wordsPerString: 2,
            join: ' ',
            formatter: function (word) {
                return word.slice(0, 1).toUpperCase() + word.slice(1)
            }
        })
        axios.post('/markers', {map_id, top: e.latlng.lat, left: e.latlng.lng, campaign_id, name, placeId })
            .then(res => {
                let marker = res.data.marker
                $('#marker-list').append(`
                    <button
                        type="button"
                        class="list-group-item list-group-item-action marker-list-button"
                        data-marker-id="${marker.id}"
                        data-place-id="${marker.place.id}"
                    >
                        ${marker.place.name}
                    </button>
                `)
                if (placeId) {
                    let $place = $(`.compendium-place[data-place-id="${placeId}"]`)
                    $place.find('.to-marker-btn').remove()
                    $place.html(`
                        ${$place.text()}
                        <i class="fa fa-map-marker-alt"></i>
                        <small class="text-muted">${mapModel.name}</small>
                    `)
                }
                let mapMarker = addMarker(marker)
                mapMarkers.push(mapMarker)
                sidebar.enablePanel('marker')
                sidebar.open('marker')
                setMarkerSidebar(marker, mapMarker)
            })
    }

    function newMarkerCancel() {
        turnOffNewMarkerEvents()
        $('#new-map-marker').remove()
        $('#map-container').css('cursor', `grab`)
        sidebar.open('compendium')
    }

    function turnOffNewMarkerEvents() {
        map.off('click', newMarkerClick)
        map.off('contextmenu', newMarkerCancel)
        map.off('mousemove', newMarkerMouseMove)
    }

    $('#new-marker').on('click', function(e) {
        addMapMarker(e)
    })

    $(document).on('click', '.to-marker-btn', function (e) {
        addMapMarker(e, $(this).data('place-id'))
    })

    $('#delete-marker').on('click', function() {
        const markerId = $('#marker-id').val()
        const placeId = $('#place-id').val()
        let thisMapMarker = mapMarkers.filter(marker => marker.options.id == markerId)[0]
        axios.delete(`/markers/${markerId}`)
            .then(res => {
                if (res.status === 200) {
                    thisMapMarker.removeFrom(map)
                    $(`.marker-list-button[data-place-id="${placeId}"]`).remove()
                    $(`.compendium-place[data-place-id="${placeId}"]`).children().remove()
                    $(`.compendium-place[data-place-id="${placeId}"]`).append(`
                        <button class="btn btn-success btn-sm float-right to-marker-btn" data-place-id="${placeId}"><i class="fa fa-map-marker-alt"></i></button>
                    `)
                    sidebar.close()
                    pnotify.success({title: res.data.message})
                }
            })
    })

    // PING MAP
    let pingTimeout, pingIcon, pingMarker, isPinging = false
    map.on('mousedown', function (e) {
        console.log(e)
        let lat = e.latlng.lat,
            lng = e.latlng.lng,
            zoom = map.getZoom(),
            point = map.latLngToLayerPoint([lat, lng])
        
        setTimeout(function () {
            $('#map-container').css('cursor', 'pointer')
        }, 500)
        pingTimeout = setTimeout(function () {
            isPinging = true
            axios.post('/maps/map_ping', { status: 'show', lat, lng, map_id })
                .then(res => {
                    console.log({res})
                    showMapPing(res.data.ping)
                })
        }, 1000)
    }).on('mouseup mousemove', function (e) {
        if (isPinging) {
            isPinging = false
            axios.post('/maps/map_ping', { status: 'remove', map_id })
            $('#map-container').css('cursor', 'grab')
            clearTimeout(pingTimeout)
            if (pingMarker) {
                pingMarker.remove(map)
            }
        }
    })

    console.log({campaignMapChannel})
    campaignMapChannel.listen('MapPinged', (e) => {
        console.log(e)
        if (e.ping.status === 'show') {
            showMapPing(e.ping)
        } else if (e.ping.status === 'remove') {
            pingMarker.remove(map)
        }
    })

    function showMapPing(ping) {
        console.log('show map ping')
        pingIcon = L.divIcon({
            className: 'outer-ping-container',
            html: `
                <div class="ping-container">
                    <div class="ping-circle" style="animation-delay: 0s"></div>
                    <div class="ping-circle" style="animation-delay: 1s"></div>
                    <div class="ping-circle" style="animation-delay: 2s"></div>
                    <div class="ping-circle" style="animation-delay: 3s"></div>
                </div>
            `
        })
        pingMarker = L.marker([ping.lat, ping.lng], { icon: pingIcon }).addTo(map)
    }
})