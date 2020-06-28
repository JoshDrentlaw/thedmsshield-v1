$(document).ready(function() {
    //DESCRIPTION
    $('#description').on('focus', function() {
        $(this).addClass('editing')
    }).on('blur', function() {
        $(this).removeClass('editing')
        let description = $(this).text()
        let id = $('#user-id').val()
        axios.post(`/dashboard/${id}/description`, { description })
            .then(res => {
                if (res.data.status === 200) {
                    $('#success-message').text(res.data.message)
                    $('#success-message-alert').removeClass('invisible')
                    setTimeout(function() {
                        $('#success-message-alert').addClass('fade')
                    }, 3000)
                }
            })
    })

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
                $('#success-message').text(res.data.message)
                $('#success-message-alert').removeClass('invisible')
                setTimeout(function() {
                    $('#success-message-alert').addClass('fade')
                }, 3000)
                $('#map-rows').append(`
                    <tr id="map-${res.data.map.id}" class="map-row">
                        <td>
                            <a class="map-link" href="/maps/${res.data.map.id}">
                                <h4>${res.data.map.map_name}</h4>
                                <img src="${res.data.map.map_preview_url}" alt="${res.data.map.map_name}" class="img-thumbnail">
                            </a>
                        </td>
                        <td><button class="btn btn-secondary config-map" data-map-id="${res.data.map.id}" data-map-name="${res.data.map.name}" data-toggle="modal" data-target="#config-map-modal">Configure</button></td>
                        <td><button class="btn btn-danger delete-map" data-map-id="${res.data.map.id}" data-toggle="modal" data-target="#delete-map-modal">Delete</button></td>
                    </tr>
                `).fadeIn()
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
                    $('#success-message').text(res.data.message)
                    $('#success-message-alert').removeClass('invisible')
                    $(`#map-${id}`).addClass('fade')
                    setTimeout(function() {
                        $(`#map-${id}`).remove()
                    }, 500)
                    setTimeout(function() {
                        $('#success-message-alert').addClass('fade')
                    }, 3000)
                }
            })
    })

    // SET DELETE MAP MODDAL
    $(document).on('click', '.delete-map', function() {
        $('#map-id').val($(this).data('map-id'))
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
                $('#success-message').text(res.data.message)
                $('#success-message-alert').removeClass('invisible')
                setTimeout(function() {
                    $('#success-message-alert').addClass('fade')
                }, 3000)
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
                    $('#success-message').text(res.data.message)
                    $('#success-message-alert').removeClass('invisible')
                    setTimeout(function() {
                        $('#success-message-alert').addClass('fade')
                    }, 3000)
                }
            })
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
                search: params.term // search term
                };
            },
        },
        dropdownParent: $('#manage-players-modal'),
        placeholder: 'Search players',
        width: '100%',
        minimumInputLength: 1,
        templateResult: customPlayerSearchResults,
        templateSelection: customPlayerSearchSelection
    })

    function customPlayerSearchSelection(state) {
        if (!state.id) {
            return state.text
        }
        return `${state.text} (Player #${state.id})`
    }

    function customPlayerSearchResults(state) {
        console.log(state)
        if (!state.id) {
            return state.text
        }

        return $(`
            <div class="media">
                <img src="${state.avatar_url ? state.avatar_url : ''}" class="mr-3" alt="player avater">
                <div class="media-body">
                    <h5 class="mt-0">${state.text}</h5>
                    ${state.description ? state.description : null}
                </div>
            </div>
        `)
    }
})