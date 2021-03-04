$(document).ready(function () {
    let placeEditor,
        creatureEditor
    const onMap = window.location.href.search('maps') >= 0,
        show = onMap ? ' show' : ''

    // SHOW CREATURE
    $(document).on('click', '.compendium-creature.show', function (e) {
        if (!$(e.target).hasClass('to-marker-btn') && $(e.target).parents('.to-marker-btn').length === 0) {
            if ($(this).data('marker-id') && map_id == $(this).data('map-id')) {
                getSelectedMarker($(this).data('marker-id'), true)
            } else if ($(this).data('marker-id') && map_id != $(this).data('map-id')) {
                getSelectedMarker($(this).data('marker-id'), false)
            } else {
                creature_id = $(this).data('creature-id')
                axios.post('/creatures/show_component', {id: creature_id, isDm})
                    .then(({ data }) => {
                        if (data.status === 200) {
                            sidebar.open('creature-marker')
                            $('#creature-marker-container').html(data.showComponent)
                        }
                    })
            }
        }
    })

    // NEW CREATURE
    $(document).on('click', '#new-creature-btn', function () {
        if (typeof sidebar !== 'undefined') {
            sidebar.close()
        }
        $('#new-creature-modal').delay('500').modal('show')
        creatureEditor = tinymceInit('new', 'creatures', {selector: '.creature-body-editor', height: 300})
    })

    // NEW CREATURE SUBMIT
    $(document).on('click', '#new-creature-submit', function () {
        let name = $('#new-creature-name').val(),
            body = tinymce.activeEditor.getContent()
        axios.post('/creatures', {name, body, campaign_id})
            .then(({ data }) => {
                if (data.status === 200) {
                    pnotify.success({title: 'New creature saved'})
                    addNewCompendiumItem(data.creature, 'creature')
                } else if (data.status === 500) {
                    pnotify.error({title: 'Error', text: 'Unable to save creature. Try again later.'})
                }
            })
            .catch((error) => {
                showValidationErrors(error.response.data.errors, 'creature')
            })
    })

    // SHOW PLACE
    $(document).on('click', '.compendium-place.show', function (e) {
        if (!$(e.target).hasClass('to-marker-btn') && $(e.target).parents('.to-marker-btn').length === 0) {
            console.log(map_id, $(this).data('map-id'), (map_id == $(this).data('map-id')))
            if ($(this).data('marker-id') && map_id == $(this).data('map-id')) {
                getSelectedMarker($(this).data('marker-id'), true)
            } else if ($(this).data('marker-id') && map_id != $(this).data('map-id')) {
                getSelectedMarker($(this).data('marker-id'), false)
            } else {
                place_id = $(this).data('place-id')
                axios.post('/places/show_component', {id: place_id, isDm})
                    .then(({ data }) => {
                        if (data.status === 200) {
                            sidebar.open('place-marker')
                            $('#place-marker-container').html(data.showComponent)
                        }
                    })
            }
        }
    })

    // NEW PLACE
    $(document).on('click', '#new-place-btn', function () {
        if (typeof sidebar !== 'undefined') {
            sidebar.close()
        }
        $('#new-place-modal').delay('500').modal('show')
        placeEditor = tinymceInit('new', 'places', {selector: '.place-body-editor', height: 300})
    })

    // NEW PLACE SUBMIT
    $(document).on('click', '#new-place-submit', function () {
        let name = $('#new-place-name').val(),
            body = tinymce.activeEditor.getContent()
        axios.post('/places', {name, body, campaign_id})
            .then(({ data }) => {
                if (data.status === 200) {
                    pnotify.success({title: 'New place saved'})
                    addNewCompendiumItem(data.place, 'place')
                } else if (data.status === 500) {
                    pnotify.error({title: 'Error', text: 'Unable to save place. Try again later.'})
                }
            })
            .catch((error) => {
                showValidationErrors(error.response.data.errors, 'place')
            })
    })

    function addNewCompendiumItem(item, itemKey, marker = false) {
        const link = onMap ? '' : ` href="/campaigns/${campaign.url}/compendium/${itemKey}/${item.url}"`
        $(`#compendium-${itemKey}s-list`).find('.first-item').remove()
        $(`#compendium-${itemKey}s-list`).append(`
            <a class="list-group-item list-group-item-action interactive dmshield-link compendium-${itemKey} compendium-item${show}" data-${itemKey}-id="${item.id}"${link}>
                ${item.name}
                <button class="btn btn-success btn-sm float-right to-marker-btn" data-${itemKey}-id="${item.id}">
                    <i class="fa fa-map-marker-alt"></i>
                </button>
            </a>
        `)
        $(`#new-${itemKey}-modal`).modal('hide')
        if (typeof sidebar !== 'undefined') {
            sidebar.open('compendium')
            $(`.compendium-${itemKey}:last-child`)[0].scrollIntoView({ behavior: 'smooth', block: 'end' })
        }
    }
})