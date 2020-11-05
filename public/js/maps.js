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

    let popup, saveTimeout, editor,
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
        tinymceInit(marker.place.id)
        console.log(mapMarkers[$('#marker-index').val()])
        let iana = luxon.local().toFormat('z')
        $('#save-time').text(luxon.fromISO(marker.place.updated_at).setZone(iana).toFormat('FF'))
    })

    function tinymceInit(id, opts = false) {
        let options = {
            selector: '.place-body-editor',
            height: 500,
            skin_url: '/css/',
            content_css: '/css/content.css',
            plugins: 'autosave',
            autosave_interval: '3s',
            autosave_prefix: '{path}-autosave-{query}',
            autosave_ask_before_unload: false,
            indent: false,
            init_instance_callback: function (editor) {
                if (id !== 'new') {
                    editor.on('input', function () {
                        if (saveTimeout) {
                            clearTimeout(saveTimeout)
                        }
                        saveTimeout = setTimeout(function () {
                            let body = tinymce.activeEditor.getContent()
                            axios.put(`/places/${id}`, {body})
                                .then(function ({ data }) {
                                    if (data.status === 200) {
                                        $('#save-time').addClass('shadow-pulse');
                                        $('#save-time').on('animationend', function(){    
                                            $('#save-time').removeClass('shadow-pulse');
                                        });
                                        let iana = luxon.local().toFormat('z')
                                        $('#save-time').text(luxon.fromISO(data.updated_at).setZone(iana).toFormat('FF'))
                                    }
                                }) 
                        }, 1000)
                    })
                }
            }
        }
        if (opts) {
            options = $.extend(true, options, opts)
        }
        editor = tinymce.init(options)
    }

    $('#change-view-btn').on('click', function () {
        let body = tinymce.activeEditor.getContent()
        tinymce.activeEditor.destroy()
        $('#editor-container').addClass('d-none')
        $('#body-display').removeClass('d-none').html(body)
    })

    /* $('#note-submit').on('click', function() {
        const $this = $(this)
        const name = $this.parent().find('#place-name').text()
        const body = tinymce.activeEditor.getContent()
        const markerId = $('#marker-id').val()
        markers = markers.map(marker => {
            if (marker.id == markerId) {
                marker.place.name = name
                marker.place.body = body
            }
            return marker
        })
        $('#marker-list').find(`[data-marker-id="${id}"]`).text(name)

        axios.put(`/places/${id}`, {name: name, body: body})
            .then(res => {
                console.log(res)
                if (res.status === 200) {
                    $('.alert').addClass('show').removeClass('invisible')
                    setTimeout(function () {
                        $('.alert').removeClass('show').addClass('fade')
                    }, 3000)
                }
            })
    }) */

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

    $('.compendium-place').on('click', function () {
        place_id = $(this).data('place-id')
        axios.post('/places/show_component', {id: place_id, isDm})
            .then(({ data }) => {
                if (data.status === 200) {
                    $('#show-place-modal').modal('show')
                    $('#show-place-modal').find('.modal-body').html(data.showComponent)
                }
            })
        sidebar.close()
    })

    // NEW PLACE
    $(document).on('click', '#new-place-btn', function () {
        sidebar.close()
        $('#new-place-modal').delay('500').modal('show')
        tinymceInit('new', {height: 300})
    })

    // NEW PLACE SUBMIT
    $(document).on('click', '#new-place-submit', function () {
        let name = $('#new-place-name').val(),
            body = tinymce.activeEditor.getContent()
        axios.post('/places', {name, body, campaign_id})
            .then(({ data }) => {
                if (data.status === 200) {
                    pnotify.success({title: 'New place saved'})
                    addNewPlaceToSidebar(data.place)
                } else if (data.status === 500) {
                    pnotify.error({title: 'Error', text: 'Unable to save place. Try again later.'})
                }
            })
            .catch((error) => {
                showValidationErrors(error.response.data.errors, 'place')
            })
    })

    function addNewPlaceToSidebar(place, marker = false) {
        campaign.places.push(place)
        $('#compendium-places-list').append(`
            <a class="list-group-item list-group-item-action interactive compendium-place" data-place-id="${place.id}">
                <h5>
                    ${place.name}
                    ${marker ?
                        `<i class="fa fa-map-marker-alt"></i>
                        <small class="text-muted">{{$place->marker->map->map_name}}</small>`
                        : ''
                    }
                </h5>
            </a>
        `)
        $('#new-place-modal').modal('hide')
        sidebar.open('compendium')
    }
})