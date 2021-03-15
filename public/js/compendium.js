$(document).ready(function () {
    const onMap = window.location.href.search('maps') >= 0,
        show = onMap ? ' show-component' : ''

    if ($('body').innerWidth() <= 460) {
        $('.compendium-btn-group').removeClass('btn-group').addClass('btn-group-vertical')
    }

    // ANCHOR e SHOW ITEM COMPONENT
    $(document).on('click', '.compendium-item.show-component', function (e) {
        if (!$(e.target).hasClass('to-marker-btn') && $(e.target).parents('.to-marker-btn').length === 0) {
            const type = $(this).data('type')

            if ($(this).data('marker-id') && map_id == $(this).data('map-id')) {
                getSelectedMarker($(this).data('marker-id'), true)
            } else if ($(this).data('marker-id') && map_id != $(this).data('map-id')) {
                getSelectedMarker($(this).data('marker-id'), false)
            } else {
                item_id = $(this).data(`${type}-id`)
                axios.post(`/${type}s/show_component`, {id: item_id, isDm})
                    .then(({ data }) => {
                        if (data.status === 200) {
                            sidebar.open(`compendium-item`)
                            $('#compendium-type-title').text(type.charAt(0).toUpperCase() + type.substr(1))
                            $(`#compendium-item-container`).html(data.showComponent)
                        }
                    })
            }
        }
    })

    // ANCHOR e UPDATE ITEM NAME (BLUR AND ENTER KEY)
    $(document).on('blur', '.show-compendium-item-name', function () {
        const name = $(this).text(),
            itemId = $('#item-id').val(),
            itemType = $('#item-type').val()

        axios.put(`/${itemType}s/${itemId}`, {type: 'edit', name})
            .then(function ({ data }) {
                if (data.status === 200) {
                    pnotify.success({title: 'Name updated!'})
                    if (!onMap) {
                        const state = {campaign_id, itemId}
                        window.history.pushState(state, '', data.redirect)
                    } else {
                        let html = name
                        if ($('#marker-id').length) {
                            html += `
                                <span class="marker-location">
                                    <i class="fa fa-map-marker-alt"></i>
                                    <small class="text-muted">${mapModel.name}</small>
                                </span>
                            `
                        } else {
                            html += `
                                <button class="btn btn-success btn-sm float-right to-marker-btn" data-${itemType}-id="${itemId}"><i class="fa fa-map-marker-alt"></i></button>
                            `
                        }
                        $(`.compendium-${itemType}[data-${itemType}-id="${itemId}"]`).html(html)
                    }
                }
            })
    })
    $(document).on('keypress', '.show-compendium-item-name', function (e) {
        if (e.key === 'Enter') {
            $('.show-compendium-item-name:visible').trigger('blur')
        }
    })

    // ANCHOR e SHOW BODY AND DM NOTE TINYMCE EDITORS
    $(document).on('click', '.show-compendium-item-body-display', function () {
        const $this = $(this),
            $editorContainer = $('.show-compendium-item-editor-container'),
            $saveTime = $editorContainer.find('.save-time'),
            itemId = $('#item-id').val(),
            itemType = $('#item-type').val()

        $editorContainer.removeClass('d-none')
        $this.addClass('d-none')
        tinymceInit(itemId, `${itemType}s`, {selector: '.show-compendium-item-body-editor', putData: {type: 'edit'}})
        if (isDm) {
            tinymceInit(itemId, `${itemType}s`, { selector: '.show-compendium-item-dm-note-editor', putData: { type: 'edit' } }, true)
        }
        let iana = luxon.local().toFormat('z')
        $saveTime.text(luxon.fromISO($saveTime.text()).setZone(iana).toFormat('FF'))
    })

    // ANCHOR e REMOVE TINYMCE EDITORS
    $(document).on('click', '.show-compendium-item-change-view-btn', function () {
        let body = tinymce.get('all-editor').getContent(),
            dmNotes
        
        if (isDm) {
            dmNotes = tinymce.get('dm-editor').getContent()
        }
        tinymce.activeEditor.destroy()
        $('.show-compendium-item-editor-container:visible').addClass('d-none')
        $('.show-compendium-item-body-display:hidden').removeClass('d-none')
        $('#body-content').html(body)
        if (isDm) {
            $('#dm-note-content').html(dmNotes)
        }
    })

    // ANCHOR e SHOW NEW ITEM MODAL
    $(document).on('click', '.new-compendium-item', function () {
        const type = $(this).data('type'),
            titleType = type.charAt(0).toUpperCase() + type.slice(1)
        if (onMap) {
            sidebar.close()
        }
        $('#new-compendium-item-modal-type').val(type)
        $('#new-compendium-item-title').text(`New ${titleType}`)
        $('#new-compendium-item-modal').delay('500').modal('show')
        $('#new-compendium-item-name').trigger('focus')
    })

    // ANCHOR e NEW ITEM SUBMIT
    $(document).on('click', '#new-compendium-item-submit', newItemSubmit)
        .on('keypress', '#new-compendium-item-name', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault()
                newItemSubmit()
            }
        })

    // ANCHOR e DELETE COMPENDIUM ITEM
    $(document).on('click', '#delete-compendium-item', function() {
        const compendiumItemId = $('#item-id').val(),
            type = $('#item-type').val()

        axios.delete(`/${type}s/${compendiumItemId}`)
            .then(res => {
                if (res.status === 200) {
                    $('#delete-compendium-item-modal').modal('hide')
                    if (onMap) {
                        sidebar.open('compendium')
                        if ($('#marker-id').val()) {
                            $('#delete-marker').trigger('click')
                        }
                    }
                }
            })
    })

    // ANCHOR e TOGGLE ITEM VISIBILITY
    /* $(document).on('click', '#place-visible', function () {
        const placeId = $('#place-id').val(),
            $this = $(this)
            visible = !$this.hasClass('btn-success')

        axios.put(`/places/${placeId}`, { type: 'visibility', map_id, visible })
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
    }) */

    // ANCHOR () ADD NEW COMPENDIUM ITEM
    function addNewCompendiumItem(item, type) {
        const link = onMap ? '' : ` href="/campaigns/${campaign.url}/compendium/${type}s/${item.url}"`
        $(`#compendium-${type}s-list`).find('.first-item').remove()
        $(`#compendium-${type}s-list`).append(`
            <a class="list-group-item list-group-item-action interactive dmshield-link compendium-${type} compendium-item${show}" data-${type}-id="${item.id}" data-type="${type}"${link}>
                ${item.name}
                ${onMap ? `
                    <button class="btn btn-success btn-sm float-right to-marker-btn" data-${type}-id="${item.id}">
                        <i class="fa fa-map-marker-alt"></i>
                    </button>
                ` : ''
                }
            </a>
        `)
        $(`#new-compendium-item-modal`).modal('hide')
        $(`#new-compendium-item-name`).val('')
        if (onMap) {
            sidebar.open('compendium')
            $(`.compendium-${type}:last-child`)[0].scrollIntoView({ behavior: 'smooth', block: 'end' })
        }
    }

    // ANCHOR () NEW ITEM SUBMIT
    function newItemSubmit() {
        const name = $('#new-compendium-item-name').val(),
            type = $('#new-compendium-item-modal-type').val()

        axios.post(`/${type}s`, {name, campaign_id})
            .then(({ data }) => {
                if (data.status === 200) {
                    pnotify.success({title: `New ${type} saved`})
                    addNewCompendiumItem(data.item, type)
                } else if (data.status === 500) {
                    pnotify.error({title: 'Error', text: `Unable to save ${type}. Try again later.`})
                }
            })
            .catch((error) => {
                showValidationErrors(error.response.data.errors, type)
            })
    }
})