$(document).ready(function() {
    //BIO
    $('#bio').on('focus', function() {
        $(this).addClass('editing')
    }).on('blur', function() {
        $(this).removeClass('editing')
        let bio = $(this).text()
        let id = $('#user-id').val()
        axios.post(`/dashboard/${id}/bio`, { bio })
            .then(res => {
                if (res.data.status === 200) {
                    pnotify.success({
                        title: 'Bio updated',
                        text: res.data.msg,
                        delay: 1000
                    })
                }
            })
    })

    // EDIT AVATAR
    $('#avatar-upload').on('submit', function(e) {
        e.preventDefault()
        let $this = $(this)
        let id = $('#user-id').val()
        let imgUpload = new FormData($(this)[0])
        axios({
            method: 'post',
            url: `/dashboard/${id}/avatar`,
            data: imgUpload,
            headers: {'Content-Type': 'multipart/form-data'}
        })
        .then(res => {
            if (res.data.status === 200) {
                let src = $('#edit-avatar').attr('src').split('/')
                src[7] = 'v' + luxon.local().valueOf()
                $('#edit-avatar').attr('src', src.join('/'))
                pnotify.success({
                    title: 'Avatar updated',
                    text: res.data.msg,
                    delay: 1000
                })
                $('#edit-avatar-modal').modal('hide')
            }
        })
    })

    // ADD NEW CAMPAIGN
    $('#new-campaign-form').on('submit', function(e) {
        e.preventDefault()
        let newCampaign = new FormData($(this)[0])
        axios({
            method: 'post',
            url: '/campaigns',
            data: newCampaign,
            headers: {'Content-Type': 'multipart/form-data'}
        })
        .then(res => {
            if (res.data.status === 200) {
                $('#new-campaign-modal').modal('hide')
                pnotify.success({
                    title: 'Campaign added',
                    text: res.data.msg,
                    delay: 1000
                })
                $('#campaign-rows').append(res.data.html).fadeIn()
            }
        })
    })

    // CHANGE TO CAMPAIGN
    // SET CONFIG CAMPAIGN MODAL
    $(document).on('click', '.config-campaign', function() {
        $('#config-campaign-name').text($(this).data('campaign-name'))
        $('#config-campaign-id').val($(this).data('campaign-id'))
    })

    // REPLACE CAMPAIGN NAME
    $('#campaign-name-form').on('submit', function(e) {
        e.preventDefault()
        const name = $('#new-campaign-name').val()
        const id = $('#config-campaign-id').val()
        axios.put(`/campaigns/${id}/name`, {name})
            .then(res => {
                if (res.status === 200) {
                    $('#new-campaign-name').val('')
                    $(`#campaign-${id}`).find('.dmshield-link').attr('href', `/campaigns/${res.data.url}`)
                    $('#config-campaign-name').text(name)
                    $(`#campaign-name-header-${id}`).text(name)
                    pnotify.success({
                        title: 'Campaign name updated',
                        text: res.data.msg,
                        delay: 1000
                    })
                }
            })
    })

    // REPLACE CAMPAIGN IMAGE
    $('#campaign-image-form').on('submit', function(e) {
        e.preventDefault()
        let newImage = new FormData($(this)[0])
        newImage.append('_method', 'PUT')
        const id = $('#config-campaign-id').val()
        axios({
            method: 'post',
            url:` /campaigns/${id}/image`,
            data: newImage,
            headers: {'Content-Type': 'multipart/form-data'}
        })
        .then(res => {
            console.log(res)
            if (res.data.status === 200) {
                let campaign = res.data.campaign
                $(`#campaign-${campaign.id}`).find('.card-img-top').replaceWith(`
                    <img id="${campaign.url}" src="${res.data.img_path + 'c_thumb,h_175,w_' + Math.floor(175 * (16 / 9)) + '/v' + luxon.local().valueOf() + '/' + campaign.cover_public_id + '.jpg'}" alt="${campaign.name}" class="card-img-top map-image-thumbnail">
                `)
                pnotify.success({
                    title: 'Campaign cover image updated.',
                    text: res.data.msg,
                    delay: 1000
                })
                $('#edit-avatar-modal').modal('hide')
            }
        })
    })

    // CONFIRM DELETION OF CAMPAIGN
    $('#confirm-delete-campaign').on('click', function() {
        const id = $('#campaign-id').val()
        axios.delete(`/campaigns/${id}`)
            .then(res => {
                if (res.data.status === 200) {
                    $('#delete-campaign-modal').modal('hide')
                    $(`#campaign-${id}`).addClass('fade')
                    setTimeout(function() {
                        $(`#campaign-${id}`).remove()
                    }, 500)
                    pnotify.success({
                        title: 'Campaign deleted',
                        text: res.data.msg,
                        delay: 1000
                    })
                }
            })
    })

    // SET DELETE CAMPAIGN MODDAL
    $(document).on('click', '.delete-campaign', function() {
        $('#campaign-id').val($(this).data('campaign-id'))
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
                    pnotify.success({
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