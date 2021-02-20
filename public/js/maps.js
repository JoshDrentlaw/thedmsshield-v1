$(document).ready(function () {
    const maxLatBound = mapHeight,
        maxLngBound = mapWidth,
        bounds = [[0,0], [maxLatBound, maxLngBound]],
        map = L.map('map-container', {
            crs: L.CRS.Simple,
            minZoom: -10,
            keepInView: true,
            zoomSnap: 0.05,
            zoomDelta: 0.5,
            // drawControl: true
        }),
        image = L.imageOverlay(mapUrl, bounds).addTo(map),
        sidebar = L.control.sidebar({
            autopan: true,
            closeButton: true,
            container: 'map-sidebar',
            position: 'left'
        }).addTo(map),
        drawnItems = new L.FeatureGroup(),
        drawControl = new L.Control.Draw({
            edit: {
                featureGroup: drawnItems
            },
            draw: {
                marker: false,
                circle: {
                    metric: false
                },
                rectangle: {
                    showArea: false,
                    metric: false
                },
                polygon: {
                    metric: false
                },
                polyline: {
                    metric: false
                },
                circlemarker: false
            }
        }),
        screenWidth = window.innerWidth,
        screenHeight = window.innerHeight - 55

    map.on('load', function (e) {
        console.log('loaded')
        setStartingZoom()
        console.log({
            mapWidth,
            mapHeight,
            zoom: map.getZoom(),
            worldBounds: map.getPixelWorldBounds(),
            size: map.getSize(),
            pixelBounds: map.getPixelBounds(),
            bounds: map.getBounds(),
            /* zoom: map.getZoom(),
            zoomScale: map.getZoomScale(),
            scaleZoom: map.getScaleZoom(),
            boundsZoom: map.getBoundsZoom() */
        })
    })

    let black = '/images/marker-icon-black.png',
        measureOptions = {
            activeColor: $(`.user-map-color[data-user-id="${user_id}"]`).val(),
            completedColor: $(`.user-map-color[data-user-id="${user_id}"]`).val()
        },
        measureControl = L.control.measure(measureOptions),
        mapMarkers = []

    map.setView([maxLatBound / 2, maxLngBound / 2], 0)
    map.addLayer(drawnItems)
    map.addControl(drawControl)
    map.addControl(measureControl)

    sidebar.on('closing', function(e) {
        this.disablePanel('marker')
        if ($('.show-place-change-view-btn').is(':visible')) {
            $('.show-place-change-view-btn').trigger('click')
        }
        mapMarkers.forEach(mapMarker => {
            mapMarker.setIcon(mapMarker.options.mainIcon)
        })
    })
    sidebar.on('content', function (e) {
        if (e.id !== 'marker') {
            if ($('.show-place-change-view-btn').is(':visible')) {
                $('.show-place-change-view-btn').trigger('click')
            }
        }
    })
    sidebar.disablePanel('marker')

    L.Edit.Circle = L.Edit.CircleMarker.extend({
        _createResizeMarker: function () {
            const center = this._shape.getLatLng(),
                resizemarkerPoint = this._getResizeMarkerPoint(center)

            this._resizeMarkers = []
            this._resizeMarkers.push(this._createMarker(resizemarkerPoint, this.options.resizeIcon))
        },

        _getResizeMarkerPoint: function (latlng) {
            const delta = this._shape._radius * Math.cos(Math.PI / 4),
                point = this._map.project(latlng)
            return this._map.unproject([point.x + delta, point.y - delta])
        },

        _resize: function (latlng) {
            const moveLatLng = this._moveMarker.getLatLng()
            let radius
        
            if (L.GeometryUtil.isVersion07x()) {
                radius = moveLatLng.distanceTo(latlng)
            }
            else {
                radius = this._map.distance(moveLatLng, latlng)
            }
        
            // **** This fixes the cicle resizing ****
            this._shape.setRadius(radius)
        
            this._map.fire(L.Draw.Event.EDITRESIZE, { layer: this._shape })
        }
    })    

    map.on('draw:created', function (e) {
        console.log(e)
        const type = e.layerType,
            layer = e.layer

        drawnItems.addLayer(layer)
    })

    function setStartingZoom() {
        // the width and height of the world is width or height * 2^zoomlevel pixels
        // 2^-1 = 0.5
        // 2^0  = 1
        // 2^1  = 2
        let i = numeral(0),
            spacer = 94 * 0
        
        if (screenWidth > 750) {
            if ((mapWidth * Math.pow(2, i.subtract(0.05).value()) + spacer) > screenWidth) {
                // ZOOM OUT
                do {
                    map.setView([maxLatBound / 2, maxLngBound / 2], i.value())
                    i.subtract(0.05)
                } while (((mapWidth * Math.pow(2, i.value())) + spacer) > screenWidth)
            } else if ((mapWidth * Math.pow(2, i.add(0.05).value()) + spacer) < screenWidth) {
                // ZOOM IN
                do {
                    map.setView([maxLatBound / 2, maxLngBound / 2], i.value())
                    i.add(0.05)
                } while (((mapWidth * Math.pow(2, i.value())) + spacer) < screenWidth)
            }
        } else {
            if ((mapWidth * Math.pow(2, i.add(0.05).value()) + spacer) < screenWidth) {
                // ZOOM IN
                do {
                    map.setView([maxLatBound / 2, maxLngBound / 2], i.value())
                    i.add(0.05)
                } while (((mapWidth * Math.pow(2, i.value())) + spacer) < screenWidth)
            }
        }
    }

    function setMeasureOptions(adding = {}, deleting = false) {
        measureOptions = $.extend(true, {}, measureOptions, adding)
        if (deleting) {
            deleting.forEach(d => delete measureOptions[d])
        }
        measureControl.remove()
        measureControl = L.control.measure(measureOptions)
        map.addControl(measureControl)
    }

    function addMarker(marker) {
        let mainIcon = new L.ExtraMarkers.icon({
            icon: `fa-${marker.icon}`,
            markerColor: marker.color,
            shape: marker.shape,
            prefix: 'fa',
            svg: true
        }),
        selectedIcon = new L.ExtraMarkers.icon({
            icon: `fa-${marker.icon}`,
            markerColor: marker.selected_color,
            shape: marker.selected_shape,
            prefix: 'fa',
            svg: true
        })
        return L
            .marker([marker.lat, marker.lng], {
                draggable: true,
                icon: mainIcon,
                id: marker.id,
                mainIcon: mainIcon,
                selectedIcon: selectedIcon
            })
            .addTo(map)
            .on('dragend', function (e) {
                const controller = Object.prototype.hasOwnProperty.call(marker, 'controller') ? marker.controller : `/markers/${marker.id}`
                axios.put(controller, {type: 'movement', lat: e.target._latlng.lat, lng: e.target._latlng.lng, map_id})
                    .then(res => {
                        if (res.status === 200) {
                            pnotify.success({title: 'Map marker position updated!'})
                        }
                    })
            })
            .on('click', function () {
                if ($('.show-place-change-view-btn').is(':visible')) {
                    $('.show-place-change-view-btn').trigger('click')
                }
                getSelectedMarker(marker.id)
            })
    }

    function setSelectedMarker(marker, setMapMarker = false) {
        mapMarkers.forEach(mapMarker => {
            if (mapMarker.options.id == marker.id) {
                mapMarker.setIcon(mapMarker.options.selectedIcon)
                if (setMapMarker) {
                    setMapMarker = mapMarker
                }
                setMarkerSidebar(marker, setMapMarker)
            } else {
                mapMarker.setIcon(mapMarker.options.mainIcon)
            }
        })
        sidebar.enablePanel('marker')
        sidebar.open('marker')
    }

    function getSelectedMarker(markerId, mapMarker = false) {
        axios.get(`/markers/${markerId}`)
            .then(res => {
                let marker = res.data
                setSelectedMarker(marker, mapMarker)
            })
    }

    // ANCHOR ADD MARKERS
    let playerMarker = addMarker({
        id: mapModel.id,
        icon: mapModel.player_marker_icon,
        shape: mapModel.player_marker_shape,
        selected_shape: mapModel.player_marker_selected_shape,
        color: mapModel.player_marker_color,
        selected_color: mapModel.player_marker_selected_color,
        lat: mapModel.player_marker_lat,
        lng: mapModel.player_marker_lng,
        controller: `/maps/${mapModel.id}/movement`
    })

    playerMarker.off('click')

    markers.map(marker => {
        mapMarkers.push(addMarker(marker))
    })

    $(document).on('click', '.marker-list-button', function() {
        getSelectedMarker($(this).data('marker-id'), true)
    })

    function setMarkerSidebar(marker, mapMarker = false) {
        let icon = marker.icon
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

        $('#marker-icon-select').select2({
            width: '100%',
            templateResult: customIconResult,
            templateSelection: customIconSelection,
            sorter: function (icons) {
                icons.sort((a, b) => a.text > b.text ? 1 : -1)
                return icons
            }
        }).val(icon).trigger('change')
    }

    // ANCHOR ICON
    $('#player-marker-icon-select').select2({
        width: '100%',
        templateResult: customIconResult,
        templateSelection: customIconSelection,
        sorter: function (icons) {
            icons.sort((a, b) => a.text > b.text ? 1 : -1)
            return icons
        }
    }).val(mapModel.player_marker_icon).trigger('change')

    $(document).on('select2:select', '#player-marker-icon-select', function () {
        updatePlayerMarkerIcon()
    })

    $(document).on('change', '#player-marker-color', function () {
        updatePlayerMarkerIcon()
    })

    $(document).on('change', '#player-marker-selected_color', function () {
        updatePlayerMarkerIcon()
    })

    function customIconSelection(icon) {
        if (!icon.id) {
            return icon.text
        }
        return $(`<i class="fa fa-${icon.id} mr-1"></i> <span>${icon.text}</span>`)
    }

    function customIconResult(icon) {
        if (!icon.id) {
            return icon.text
        }
        return $(`<i class="fa fa-${icon.id} mr-1"></i> <span>${icon.text}</span>`)
    }

    function updatePlayerMarkerIcon() {
        const id = mapModel.id,
            icon = $('#player-marker-icon-select').val(),
            color = $('#player-marker-color').val()/* ,
            selected_color = $('#player-marker-selected_color').val() */

        axios.put(`/maps/${id}/player_marker`, { icon, color, selected_color: color, map_id })
            .then(res => {
                let mainIcon = new L.ExtraMarkers.icon({
                        icon: `fa-${icon}`,
                        markerColor: color,
                        shape: 'star',
                        prefix: 'fa',
                        svg: true
                    })/* ,
                    selectedIcon = new L.ExtraMarkers.icon({
                        icon: `fa-${icon}`,
                        markerColor: color,
                        shape: 'star',
                        prefix: 'fa',
                        svg: true
                    }) */
                playerMarker.options.mainIcon = mainIcon
                // playerMarker.options.selectedIcon = selec                           tedIcon
                playerMarker.setIcon(playerMarker.options.mainIcon)
            })
    }

    function updateMarkerIcon(id, icon) {
        mapMarkers.forEach(marker => {
            if (marker.options.id == id) {
                let mainIcon = new L.ExtraMarkers.icon({
                        icon: `fa-${icon}`,
                        markerColor: 'blue',
                        shape: 'circle',
                        prefix: 'fa'
                    }),
                    selectedIcon = new L.ExtraMarkers.icon({
                        icon: `fa-${icon}`,
                        markerColor: 'green',
                        shape: 'square',
                        prefix: 'fa'
                    })
                marker.options.mainIcon = mainIcon
                marker.options.selectedIcon = selectedIcon
            }
        })
    }

    $(document).on('select2:select', '#marker-icon-select', function () {
        let id = $('#marker-id').val(),
            icon = $(this).val()

        axios.put(`/markers/${id}`, {type: 'icon', icon, map_id})
            .then(res => {
                if (res.status === 200) {
                    pnotify.success({ title: 'Map marker icon updated!' })
                    updateMarkerIcon(id, icon)
                    getSelectedMarker(id)
                }
            })
    })

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

    campaignMapChannel.listen('MarkerUpdate', (e) => {
        if (e.markerUpdate.marker_type === 'map') {
            let marker = mapMarkers.filter(m => m.options.id == e.markerUpdate.id)[0]
            if (e.markerUpdate.update_type === 'movement') {
                marker.setLatLng(L.latLng(e.markerUpdate.lat, e.markerUpdate.lng))
            } else if (e.markerUpdate.update_type === 'icon') {
                updateMarkerIcon(e.markerUpdate.id, e.markerUpdate.icon)
                marker = mapMarkers.filter(m => m.options.id == e.markerUpdate.id)[0]
                marker.setIcon(marker.options.mainIcon)
            }
        } else if (e.markerUpdate.marker_type === 'player') {
            if (e.markerUpdate.update_type === 'movement') {
                playerMarker.setLatLng(L.latLng(e.markerUpdate.lat, e.markerUpdate.lng))
            } else if (e.markerUpdate.update_type === 'player_marker') {
                let mainIcon = new L.ExtraMarkers.icon({
                        icon: `fa-${e.markerUpdate.icon}`,
                        markerColor: e.markerUpdate.color,
                        shape: 'star',
                        prefix: 'fa',
                        svg: true
                    })
                playerMarker.options.mainIcon = mainIcon
                playerMarker.setIcon(playerMarker.options.mainIcon)
            }
        }
    })

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

    // ANCHOR USER COLOR
    $(document).on('change', '.user-map-color', function () {
        let map_color = $(this).val(),
            id = $(this).data('user-id')

        axios.post('/maps/user_map_color', { map_color, id, map_id })
            .then(() => {
                updateColor()
            })
    })

    campaignMapChannel.listen('UserMapUpdate', function (e) {
        if (e.update.hasOwnProperty('map_color')) {
            $(`.user-map-color[data-user-id="${e.update.user_id}"]`).val(e.update.map_color)
        }
    })

    function updateColor() {
        setMeasureOptions({
            activeColor: $(`.user-map-color[data-user-id="${user_id}"]`).val(),
            completedColor: $(`.user-map-color[data-user-id="${user_id}"]`).val()
        })
    }

    // ANCHOR PING MAP
    let eLat, eLng,
        mapDrag = false,
        pingMarkers = [],
        isPinging = false

    map.on('contextmenu', function (e) {
        if (!mapDrag) {
            let lat = e.latlng.lat,
                lng = e.latlng.lng

            eLat = lat
            eLng = lng

            isPinging = true
            axios.post('/maps/map_ping', { status: 'show', lat, lng, map_id, user_id })
                .then(res => {
                    let pingPromise = new Promise((resolve, rej) => {
                        resolve(showMapPing(res.data.ping))
                    })
                    pingPromise.then(res => {
                        removeMapPing()
                    })
                })
        }
    })/* .on('mouseup', function () {
        setTimeout(function () {
            mapDrag = false
        }, 100)
    }).on('movestart', function () {
        mapDrag = true
    }) */

    campaignMapChannel.listen('MapPinged', (e) => {
        if (e.ping.status === 'show') {
            showMapPing(e.ping)
        } else if (e.ping.status === 'remove') {
            console.log(pingMarkers)
            if (pingMarkers.length > 0) {
                const thisPing = pingMarkers.shift()
                thisPing.remove(map)
                console.log(pingMarkers)
            }
        }
    })

    function showMapPing(ping) {
        const userMapColor = $(`.user-map-color[data-user-id="${ping.user_id}"]`).val(),
            pingIcon = L.divIcon({
                className: 'outer-ping-container',
                html: `
                    <div class="ping-container">
                        <div class="ping-circle" style="animation-delay:0s;background-color:${userMapColor};"></div>
                        <div class="ping-circle" style="animation-delay:1s;background-color:${userMapColor};"></div>
                        <div class="ping-circle" style="animation-delay:2s;background-color:${userMapColor};"></div>
                        <div class="ping-circle" style="animation-delay:3s;background-color:${userMapColor};"></div>
                    </div>
                `
            })
        const thisPing = L.marker([ping.lat, ping.lng], { icon: pingIcon }).addTo(map)
        pingMarkers.push(thisPing)
    }

    function removeMapPing(e) {
        console.log(pingMarkers)
        setTimeout(function () {
            if (pingMarkers.length > 0) {
                $('#map-container').css('cursor', 'grab')
                axios.post('/maps/map_ping', { status: 'remove', map_id })
                    .then(res => {
                        const thisPing = pingMarkers.shift()
                        thisPing.remove(map)
                    })
            }
            if (isPinging) {
                isPinging = false
            }
        }, 5000)
    }

    // ANCHOR TOOLBAR
    /* const mapPingAction = L.Toolbar2.Action.extend({
        options: {
            toolbarIcon: {
                html: '<i></i>',
                className: 'fa fa-bullseye',
                tooltip: 'Ping the map.'
            }
        },
        addHooks: function (e) {
            console.log(e)
            let lat = e.latlng.lat,
            lng = e.latlng.lng

            eLat = lat
            eLng = lng
            axios.post('/maps/map_ping', { status: 'show', lat, lng, map_id, user_id })
                .then(res => {
                    console.log({res})
                    showMapPing(res.data.ping)
                })
        }
    })
    new L.Toolbar2.Control({
        position: 'topright',
        actions: [mapPingAction]
    }).addTo(map) */
})