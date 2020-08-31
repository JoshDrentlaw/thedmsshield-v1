$(document).ready(function() {
    // ADD NEW MAP
    $('#map-upload').on('submit', function(e) {
        e.preventDefault()
        let imgUpload = new FormData($(this)[0])
        axios({
            method: 'post',
            url: '/maps',
            data: imgUpload,
            headers: {'Content-Type': 'multipart/form-data'}
        })
        .then(res => {
            if (res.data.status === 200) {
                $('#add-map-modal').modal('hide')
                PNotify.success({
                    title: 'Map added',
                    text: res.data.msg,
                    delay: 1000
                })
                $('#map-rows').append(res.data.html).fadeIn()
            }
        })
    })

    // SET CONFIG MAP MODAL
    $(document).on('click', '.config-map', function() {
        $('#config-map-name').text($(this).data('map-name'))
        $('#config-map-id').val($(this).data('map-id'))
    })

    // REPLACE MAP WITH NEW IMAGE
    $('#new-map-form').on('submit', function(e) {
        e.preventDefault()
        const id = $('#config-map-id').val()
        let newImageUpload = new FormData($(this)[0])
        newImageUpload.append('_method', 'PUT')
        axios.post(`/maps/${id}/image`, newImageUpload, {
            headers: {'Content-Type': 'multipart/form-data'}
        })
        .then(res => {
            if (res.status === 200) {
                $('#new-map-image').val('')
                PNotify.success({
                    title: 'Map updated',
                    text: res.data.msg,
                    delay: 1000
                })
                let d = new Date()
                $(`#${res.data.map.map_url}`).attr('src', res.data.map.map_preview_url + '?' + d.getTime())
            }
        })
    })

    // REPLACE MAP NAME
    $('#map-name-form').on('submit', function(e) {
        e.preventDefault()
        const map_name = $('#new-map-name').val()
        const id = $('#config-map-id').val()
        axios.put(`/maps/${id}/name`, {map_name})
            .then(res => {
                if (res.status === 200) {
                    $('#new-map-name').val('')
                    $(`#map-${id}`).find('.map-link').attr('href', `/maps/${res.data.map_url}`)
                    $('#config-map-name').text(map_name)
                    $(`#map-name-header-${id}`).text(map_name)
                    PNotify.success({
                        title: 'Map name updated',
                        text: res.data.msg,
                        delay: 1000
                    })
                }
            })
    })

    // CONFIRM DELETION OF MAP
    $('#confirm-delete-map').on('click', function() {
        const id = $('#map-id').val()
        axios.delete(`/maps/${id}`)
            .then(res => {
                if (res.data.status === 200) {
                    $('#delete-map-modal').modal('hide')
                    $(`#map-${id}`).addClass('fade')
                    setTimeout(function() {
                        $(`#map-${id}`).remove()
                    }, 500)
                    PNotify.success({
                        title: 'Map deleted',
                        text: res.data.msg,
                        delay: 1000
                    })
                }
            })
    })

    // SET DELETE MAP MODDAL
    $(document).on('click', '.delete-map', function() {
        $('#map-id').val($(this).data('map-id'))
    })

    /*===========================*
    *
    *
    *      MANAGE  PLAYERS
    * 
    * 
    *============================*/

    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $('#player-search').select2({
        ajax: {
            url: '/dashboard/player_search',
            type: 'post',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                _token: CSRF_TOKEN,
                search: params.term, // search term
                id: $('#user-id').val()
                };
            },
        },
        dropdownParent: $('#add-players-modal'),
        placeholder: 'Search players',
        theme: 'bootstrap4',
        minimumInputLength: 1,
        templateResult: customPlayerSearchResults,
        // templateSelection: customPlayerSearchSelection
    })

    function customPlayerSearchSelection(state) {
        if (!state.id) {
            return state.text
        }
        return `${state.text} (Player #${state.id})`
    }

    function customPlayerSearchResults(state) {
        if (!state.id) {
            return state.text
        }

        return $(`
            <div class="media">
                ${
                    state.avatar_url_small ?
                    `<img src="${state.avatar_url_small}" class="mr-3" alt="player avater">` :
                    `<div style="width:64px;height:64px;padding:0.5em;"><i class="w-100 h-100 fa fa-user"></i></div>`
                }
                <div class="media-body">
                    <h5 class="mt-0">${state.text}</h5>
                    ${state.bio ? state.bio : '<i>No bio...</i>'}
                </div>
            </div>
        `)
    }

    // SET ADD PLAYERS MODAL
    $(document).on('click', '.add-players', function() {
        $('#player-search').val(null).trigger('change')
        let id = $(this).data('campaign-id')
        $('#add-player-campaign-id').val(id)
        axios.post('/dashboard/get_pending_players', { id })
            .then(res => {
                if (res.status === 200) {
                    $('#pending-invite-list').html(res.data.html)
                }
            })
    })

    // SEND PLAYER REQUEST
    $('#confirm-add-player').on('click', function() {
        let id = parseInt($('#add-player-campaign-id').val())
        let playerId = parseInt($('#player-search').val())
        axios.post(`/dashboard/send_player_invite`, { id, playerId })
            .then(res => {
                if (res.status === 200) {
                    PNotify.success({
                        title: 'Invite sent',
                        text: res.data.msg,
                        delay: 1000
                    })
                    $('#pending-invite-list').children().remove()
                    $('#pending-invite-list').html(res.data.html).fadeIn()
                }
            })
    })
})