$(document).ready(function () {
    const maxLatBound = mapHeight,
        maxLngBound = mapWidth,
        bounds = [[0,0], [maxLatBound, maxLngBound]],
        image = L.imageOverlay(mapUrl, bounds).addTo(map),
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
        setStartingZoom()
    })

    let black = '/images/marker-icon-black.png',
        measureOptions = {
            activeColor: $(`.user-map-color[data-user-id="${user_id}"]`).val(),
            completedColor: $(`.user-map-color[data-user-id="${user_id}"]`).val()
        },
        measureControl = L.control.measure(measureOptions)

    map.setView([maxLatBound / 2, maxLngBound / 2], 0)
    map.addLayer(drawnItems)
    map.addControl(drawControl)
    map.addControl(measureControl)

    
    sidebar = L.control.sidebar({
        autopan: true,
        closeButton: true,
        container: 'map-sidebar',
        position: 'left'
    }).addTo(map)
    sidebar.on('closing', function(e) {
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
        
        // DESKTOP
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
            // MOBILE
            if ((mapWidth * Math.pow(2, i.add(0.05).value()) + spacer) < screenWidth) {
                // ZOOM IN
                do {
                    map.setView([maxLatBound / 2, maxLngBound / 2], i.value())
                    i.add(0.05)
                } while (i.value() <= 0.2 && ((mapWidth * Math.pow(2, i.value())) + spacer) < screenWidth)
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
        const mainIcon = new L.ExtraMarkers.icon({
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
        let type

        if (marker.place_id) {
            type = 'place'
        } else if (marker.creature_id) {
            type = 'creature'
        } else if (marker.organization_id) {
            type = 'organization'
        } else if (marker.item_id) {
            type = 'item'
        } else if (marker.player_id) {
            type = 'player'
        }

        let mapMarker = L
            .marker([marker.lat, marker.lng], {
                draggable: isDm && !marker.locked,
                icon: mainIcon,
                id: marker.id,
                mainIcon: mainIcon,
                selectedIcon: selectedIcon,
                type
            })
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
                if ($(`.show-${type}-change-view-btn`).is(':visible')) {
                    $(`.show-${type}-change-view-btn`).trigger('click')
                }
                getSelectedMarker(marker.id, true)
            })

        if (
            type === 'player' ||
            isDm ||
            (
                !isDm &&
                marker[type].visible &&
                marker.visible
            )
        ) {
            mapMarker.addTo(map)
        }

        return mapMarker
    }

    setSelectedMarker = function (marker, setMapMarker = false, type = false) {
        mapMarkers.forEach(mapMarker => {
            if (mapMarker.options.id == marker.id) {
                mapMarker.setIcon(mapMarker.options.selectedIcon)
                type = mapMarker.options.type
                if (setMapMarker) {
                    setMapMarker = mapMarker
                }
            } else {
                mapMarker.setIcon(mapMarker.options.mainIcon)
            }
        })
        setMarkerSidebar(marker, setMapMarker)
        sidebar.open(`compendium-item`)
    }

    getSelectedMarker = function(markerId, mapMarker = false) {
        axios.get(`/markers/${markerId}`)
            .then(res => {
                let marker = res.data.marker,
                    type

                if (marker.place_id) {
                    type = 'place'
                } else if (marker.creature_id) {
                    type = 'creature'
                } else if (marker.organization_id) {
                    type = 'organization'
                } else if (marker.item_id) {
                    type = 'item'
                }

                $(`#compendium-type-title`).text(type.charAt(0).toUpperCase() + type.substr(1))
                $(`#compendium-item-container`).html(res.data.showComponent)
                setSelectedMarker(marker, mapMarker, type)
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
        controller: `/maps/${mapModel.id}/movement`,
        place_id: false,
        creature_id: false,
        organiztion_id: false,
        item_id: false,
        player_id: true
    })

    playerMarker.off('click')

    markers.map(marker => {
        mapMarkers.push(addMarker(marker))
    })

    function setMarkerSidebar(marker, mapMarker = false) {
        let icon = marker.icon,
            type

        if (marker.place) {
            type = 'place'
        } else if (marker.creature) {
            type = 'creature'
        } else if (marker.organization) {
            type = 'organization'
        } else if (marker.item) {
            type = 'item'
        }

        if (mapMarker && (isDm || (!isDm && marker[type].visible && marker.visible))) {
            let markerLatLng = mapMarker.getLatLng()
            markerLatLng = {
                lat: markerLatLng.lat,
                lng: markerLatLng.lng - 100
            }
            map.flyTo(markerLatLng, 0.5, {duration: 1, easeLinearity: 1})
        }

        $('#marker-id').val(marker.id)

        if (marker.place) {
            $('#item-id').val(marker.place.id)
        } else if (marker.creature) {
            $('#item-id').val(marker.creature.id)
        } else if (marker.organization) {
            $('#item-id').val(marker.organization.id)
        } else if (marker.item) {
            $('#item-id').val(marker.item.id)
        }

        markerIconSelect2(icon)
    }

    $(document).on('click', '#show-to-players', function () {
        const id = $(this).data('id'),
            type = $(this).data('type'),
            t = type.substr(0, (type.length - 1))

        if ($(`#${t}-visible`).hasClass('btn-danger')) {
            $(`#${t}-visible`).trigger('click')
        }
        axios.post(`/${type}/show_to_players/${id}`)
    })

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
                let color, selectedColor, shape
                if (marker.options.type === 'place') {
                    color = 'blue'
                    selectedColor = 'green'
                    shape = 'circle'
                } else if (marker.options.type === 'creature') {
                    color = 'orange'
                    selectedColor = 'yellow'
                    shape = 'penta'
                } else if (marker.options.type === 'organization') {
                    color = 'orange'
                    selectedColor = 'yellow'
                    shape = 'penta'
                } else if (marker.options.type === 'item') {
                    color = 'orange'
                    selectedColor = 'yellow'
                    shape = 'penta'
                }
                let mainIcon = new L.ExtraMarkers.icon({
                        icon: `fa-${icon}`,
                        markerColor: color,
                        shape,
                        prefix: 'fa'
                    }),
                    selectedIcon = new L.ExtraMarkers.icon({
                        icon: `fa-${icon}`,
                        markerColor: selectedColor,
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

    function addMapMarker(e, id, type) {
        sidebar.close()
        $('#map-container').append(`<img id="new-map-marker" data-${type}-id="${id}" src="${black}" alt="Black map marker icon">`)
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
        let id, type
        if ($('#new-map-marker').data('place-id')) {
            id = $('#new-map-marker').data('place-id')
            type = 'place'
        } else if ($('#new-map-marker').data('creature-id')) {
            id = $('#new-map-marker').data('creature-id')
            type = 'creature'
        }
        $('#new-map-marker').remove()
        $('#map-container').css('cursor', `grab`)
        const $item = $(`.compendium-${type}[data-${type}-id="${id}"]`)
        $item.find('.to-marker-btn').remove()
        $item.html(`
            ${$item.text()}
            <span class="marker-location">
                <i class="fa fa-map-marker-alt"></i>
                <small class="text-muted">${mapModel.name}</small>
            </span>
        `)
        axios.post('/markers', { map_id, lat: e.latlng.lat, lng: e.latlng.lng, id, type })
            .then(res => {
                showNewMarker(res.data.marker, type)
            })
    }

    function showNewMarker(marker, type) {
        $(`.compendium-${type}[data-${type}-id="${marker[type].id}"]`).attr('data-marker-id', marker.id)
        let mapMarker = addMarker(marker)
        mapMarkers.push(mapMarker)
        getSelectedMarker(marker.id, true)
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
            } else if (e.markerUpdate.update_type === 'delete') {
                let thisMapMarker = mapMarkers.filter(marker => marker.options.id == e.markerUpdate.marker.id)[0]
                deleteMapMarker(thisMapMarker, e.markerUpdate.compendium_item_id, e.markerUpdate.compendium_type)
            } else if (e.markerUpdate.update_type === 'visibility') {
                mapMarkers.forEach(mapMarker => {
                    if (mapMarker.options.id == e.markerUpdate.id) {
                        const $compendiumItem = $(`.compendium-${mapMarker.options.type}[data-marker-id="${e.markerUpdate.id}"]`)
                        if (e.markerUpdate.visible) {
                            mapMarker.addTo(map)
                            $('#marker-location').removeClass('d-none')
                            $compendiumItem.find('.marker-location').removeClass('d-none')
                        } else {
                            mapMarker.removeFrom(map)
                            $('#marker-location').addClass('d-none')
                            $compendiumItem.find('.marker-location').addClass('d-none')
                        }
                    }
                })
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

    $(document).on('click', '.to-marker-btn', function (e) {
        if ($(this).data('place-id')) {
            addMapMarker(e, $(this).data('place-id'), 'place')
        } else if ($(this).data('creature-id')) {
            addMapMarker(e, $(this).data('creature-id'), 'creature')
        } else if ($(this).data('organization-id')) {
            addMapMarker(e, $(this).data('organization-id'), 'organization')
        } else if ($(this).data('item-id')) {
            addMapMarker(e, $(this).data('item-id'), 'item')
        }
    })

    $(document).on('click', '#lock-marker', function () {
        const markerId = $('#marker-id').val(),
            $this = $(this)
            locked = !$this.hasClass('btn-danger')

        axios.put(`/markers/${markerId}`, { type: 'lock', map_id, locked })
            .then(res => {
                if (res.status === 200) {
                    $this.toggleClass('btn-danger btn-success')
                    $this.children().remove()
                    let icon
                    if (locked) {
                        icon = 'fa-lock'
                    } else {
                        icon = 'fa-lock-open'
                    }
                    $this.append(`<i class="fa ${icon}"></i>`)
                    mapMarkers.forEach(mapMarker => {
                        if (mapMarker.options.id == markerId) {
                            mapMarker.removeFrom(map)
                            if (locked) {
                                mapMarker.options.draggable = false
                            } else {
                                mapMarker.options.draggable = true
                            }
                            mapMarker.addTo(map)
                        }
                    })
                }
            })
    })

    $(document).on('click', '#marker-visible', function () {
        const markerId = $('#marker-id').val(),
            $this = $(this),
            type = $this.data('type'),
            visible = !$this.hasClass('btn-success')

        if ($(`#${type}-visible`).hasClass('btn-success')) {
            axios.put(`/markers/${markerId}`, { type: 'visibility', map_id, visible })
                .then(res => {
                    if (res.status === 200) {
                        $this.toggleClass('btn-danger btn-success')
                        $this.children().remove()
                        let icon
                        if (visible) {
                            icon = 'fa-eye'
                        } else {
                            icon = 'fa-eye-slash'
                        }
                        $this.append(`<i class="fa ${icon}"></i>`)
                    }
                })
        }
        
    })

    $(document).on('click', '#delete-marker', function() {
        const markerId = $('#marker-id').val()
        let compendiumItemId,
            type,
            thisMapMarker = mapMarkers.filter(marker => marker.options.id == markerId)[0]

        if (thisMapMarker.options.type === 'place') {
            compendiumItemId = $('#place-id').val()
            type = 'place'
        } else if (thisMapMarker.options.type === 'creature') {
            compendiumItemId = $('#creature-id').val()
            type = 'creature'
        } else if (thisMapMarker.options.type === 'organization') {
            compendiumItemId = $('#organization-id').val()
            type = 'organization'
        } else if (thisMapMarker.options.type === 'item') {
            compendiumItemId = $('#item-id').val()
            type = 'item'
        }
        axios.delete(`/markers/${markerId}`)
            .then(res => {
                if (res.status === 200) {
                    deleteMapMarker(thisMapMarker, compendiumItemId, type)
                }
            })
    })

    function deleteMapMarker(marker, id, type) {
        marker.removeFrom(map)
        $(`.marker-list-button[data-${type}-id="${id}"]`).remove()
        $(`.compendium-${type}[data-${type}-id="${id}"]`).children().remove()
        $(`.compendium-${type}[data-${type}-id="${id}"]`).removeAttr('data-marker-id')
        if (isDm) {
            $(`.compendium-${type}[data-${type}-id="${id}"]`).append(`
                <button class="btn btn-success btn-sm float-right to-marker-btn" data-${type}-id="${id}"><i class="fa fa-map-marker-alt"></i></button>
            `)
            $('#marker-options').remove()
        }
        pnotify.success({ title: 'Marker deleted' })
        $('#delete-marker-modal').modal('hide')
    }

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
    let mapDrag = false,
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
    })

    campaignMapChannel.listen('MapPinged', (e) => {
        if (e.ping.status === 'show') {
            showMapPing(e.ping)
        } else if (e.ping.status === 'remove') {
            if (pingMarkers.length > 0) {
                const thisPing = pingMarkers.shift()
                thisPing.remove(map)
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