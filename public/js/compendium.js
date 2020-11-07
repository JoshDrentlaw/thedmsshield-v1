$(document).ready(function () {
    let placeEditor, thingEditor, ideaEditor, creatureEditor

    // SHOW CREATURE
    $('.compendium-creature').on('click', function () {
        creature_id = $(this).data('creature-id')
        axios.post('/creatures/show_component', {id: creature_id, isDm})
            .then(({ data }) => {
                if (data.status === 200) {
                    $('#show-creature-modal').modal('show')
                    $('#show-creature-modal').find('.modal-body').html(data.showComponent)
                }
            })
        if (typeof sidebar !== 'undefined') {
            sidebar.close()
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
    $('.compendium-place').on('click', function () {
        place_id = $(this).data('place-id')
        axios.post('/places/show_component', {id: place_id, isDm})
            .then(({ data }) => {
                if (data.status === 200) {
                    $('#show-place-modal').modal('show')
                    $('#show-place-modal').find('.modal-body').html(data.showComponent)
                }
            })
        if (typeof sidebar !== 'undefined') {
            sidebar.close()
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

    // SHOW THING
    $('.compendium-thing').on('click', function () {
        thing_id = $(this).data('thing-id')
        axios.post('/things/show_component', {id: thing_id, isDm})
            .then(({ data }) => {
                if (data.status === 200) {
                    $('#show-thing-modal').modal('show')
                    $('#show-thing-modal').find('.modal-body').html(data.showComponent)
                }
            })
        if (typeof sidebar !== 'undefined') {
            sidebar.close()
        }
    })

    // NEW THING
    $(document).on('click', '#new-thing-btn', function () {
        if (typeof sidebar !== 'undefined') {
            sidebar.close()
        }
        $('#new-thing-modal').delay('500').modal('show')
        thingEditor = tinymceInit('new', 'things', {selector: '.thing-body-editor', height: 300})
    })

    // NEW THING SUBMIT
    $(document).on('click', '#new-thing-submit', function () {
        let name = $('#new-thing-name').val(),
            body = tinymce.activeEditor.getContent()
        axios.post('/things', {name, body, campaign_id})
            .then(({ data }) => {
                if (data.status === 200) {
                    pnotify.success({title: 'New thing saved'})
                    addNewCompendiumItem(data.thing, 'thing')
                } else if (data.status === 500) {
                    pnotify.error({title: 'Error', text: 'Unable to save thing. Try again later.'})
                }
            })
            .catch((error) => {
                showValidationErrors(error.response.data.errors, 'thing')
            })
    })

    // SHOW IDEA
    $('.compendium-idea').on('click', function () {
        idea_id = $(this).data('idea-id')
        axios.post('/ideas/show_component', {id: idea_id, isDm})
            .then(({ data }) => {
                if (data.status === 200) {
                    $('#show-idea-modal').modal('show')
                    $('#show-idea-modal').find('.modal-body').html(data.showComponent)
                }
            })
        if (typeof sidebar !== 'undefined') {
            sidebar.close()
        }
    })

    // NEW IDEA
    $(document).on('click', '#new-idea-btn', function () {
        if (typeof sidebar !== 'undefined') {
            sidebar.close()
        }
        $('#new-idea-modal').delay('500').modal('show')
        ideaEditor = tinymceInit('new', 'ideas', {selector: '.idea-body-editor', height: 300})
    })

    // NEW IDEA SUBMIT
    $(document).on('click', '#new-idea-submit', function () {
        let name = $('#new-idea-name').val(),
            body = tinymce.activeEditor.getContent()
        axios.post('/ideas', {name, body, campaign_id})
            .then(({ data }) => {
                if (data.status === 200) {
                    pnotify.success({title: 'New idea saved'})
                    addNewCompendiumItem(data.idea, 'idea')
                } else if (data.status === 500) {
                    pnotify.error({title: 'Error', text: 'Unable to save idea. Try again later.'})
                }
            })
            .catch((error) => {
                showValidationErrors(error.response.data.errors, 'idea')
            })
    })

    function addNewCompendiumItem(item, itemKey, marker = false) {
        campaign[`${itemKey}s`].push(item)
        $(`#compendium-${itemKey}s-list`).append(`
            <a class="list-group-item list-group-item-action dmshield-link interactive compendium-${itemKey}" data-${itemKey}-id="${item.id}">
                ${item.name}
                ${marker ?
                    `<i class="fa fa-map-marker-alt"></i>
                    <small class="text-muted">${mapModel.name}</small>`
                    : ''
                }
            </a>
        `)
        $(`#new-${itemKey}-modal`).modal('hide')
        if (typeof sidebar !== 'undefined') {
            sidebar.open('compendium')
        }
    }
})