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
    let black = 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-black.png'
    /* let blackIcon = new L.Icon({
        iconUrl: black,
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    }) */

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
        if ($('#change-view-btn').is(':visible')) {
            $('#change-view-btn').trigger('click')
        }
    })
    sidebar.on('content', function (e) {
        if (e.id !== 'marker') {
            if ($('#change-view-btn').is(':visible')) {
                $('#change-view-btn').trigger('click')
            }
        }
    })
    sidebar.disablePanel('marker')

    function addMarker(marker) {
        return L
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
    }

    // ADD MARKERS
    markers.map(marker => {
        addMarker(marker)
    })

    $('.marker-button').on('click', function() {
        sidebar.enablePanel('marker')
        sidebar.open('marker')
        let markerId = $(this).data('marker-id')
        let thisMarker = markers.filter(marker => marker.id == markerId)[0]
        let markerIndex = $(this).data('marker-index')
        let thisMapMarker = mapMarkers.filter(marker => {
            if (marker.index == markerIndex) {
                marker.marker.setIcon(greenIcon)
                return marker
            }
        })
        mapMarkers.filter(marker => {
            if (marker.index != markerIndex) {
                marker.marker.setIcon(blueIcon)
            }
        })
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

    $('#new-marker').on('click', function(e) {
        sidebar.close()
        $('#map-container').append(`<img id="new-map-marker" src="${black}" alt="Black map marker icon">`)
        $('#new-map-marker').css({
            position: 'fixed',
            top: e.offsetX + 12.5,
            left: e.offsetY - 12,
            zIndex: '5000'
        })
        $('#map-container').css('cursor', `pointer`)
        let mousemove = map.on('mousemove', function (e) {
            $('#new-map-marker').css({
                top: e.originalEvent.offsetY + 12.5,
                left: e.originalEvent.offsetX - 12
            })
        })
        let click = map.on('click', function (e) {
            mousemove.off()
            click.off()
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
            axios.post('/markers', {map_id, top: e.latlng.lat, left: e.latlng.lng, campaign_id, name })
                .then(res => {
                    $('#marker-list').append(`
                        <button type="button" class="list-group-item list-group-item-action marker-button" data-marker-index="${mapMarker.index}" data-marker-id="${marker.id}">${place.name}</button>
                    `)
                    sidebar.enablePanel('marker')
                    sidebar.open('marker')
                    setMarkerSidebar(marker)
                })
                .catch(rej => {
                    console.log(rej)
                })
        })
    })

    $(document).on('click', '.to-marker-btn', function () {
        sidebar.close()
        $('#map-container').css('cursor', `url(${black}), auto`)
        map.on('click', function (e) {
            axios.post('/markers', {map_id, top: e.latlng.lat, left: e.latlng.lng, campaign_id})
                .then(res => {
                    $('#map-container').css('cursor', `grab`)
                    let marker = res.data.marker
                    let place = res.data.place
                    marker.place = place
                    addMarker(marker)
                    $('#marker-list').append(`
                        <a class="list-group-item list-group-item-action interactive dmshield-link compendium-place" data-place-id="${place.id}">
                            ${place.name}
                            <i class="fa fa-map-marker-alt"></i>
                            <small class="text-muted">${mapModel.name}</small>
                        </a>
                    `)
                    mapMarkers.push(marker)
                    sidebar.open('marker')
                    $('#place-name').focus()
                    setMarkerSidebar(marker)
                })
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