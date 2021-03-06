$(document).ready(function () {
    $(document).on('blur', '.show-place-name', function () {
        let name = $(this).text()
        if (!place_id) {
            place_id = $('#place-id').val()
        }
        axios.put(`/places/${place_id}`, {type: 'edit', name})
            .then(function ({ data }) {
                if (data.status === 200) {
                    pnotify.success({title: 'Name updated!'})
                    let pathname = document.location.pathname.split('/')
                    pathname = pathname[pathname.length - 2]
                    if (pathname === 'places') {
                        const state = {campaign_id, place_id}
                        window.history.pushState(state, '', data.redirect)
                    } else if (pathname === 'maps') {
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
                                <button class="btn btn-success btn-sm float-right to-marker-btn" data-place-id="${place_id}"><i class="fa fa-map-marker-alt"></i></button>
                            `
                        }
                        $(`.compendium-place[data-place-id="${place_id}"]`).html(html)
                    }
                }
            })
    })
    $(document).on('keypress', '.show-place-name', function (e) {
        if (e.key === 'Enter') {
            $('.show-place-name:visible').trigger('blur')
        }
    })

    $(document).on('click', '.show-place-body-display', function () {
        let $this = $(this)
        let $editorContainer = $this.siblings('.show-place-editor-container')
        let $saveTime = $editorContainer.find('.save-time')
        let placeId = $this.siblings('#place-id').val()
        $editorContainer.removeClass('d-none')
        $this.addClass('d-none')
        tinymceInit(placeId, 'places', {selector: '.show-place-body-editor', putData: {type: 'edit'}})
        if (isDm) {
            tinymceInit(placeId, 'places', { selector: '.show-place-dm-note-editor', putData: { type: 'edit' } }, true)
        }
        let iana = luxon.local().toFormat('z')
        $saveTime.text(luxon.fromISO($saveTime.text()).setZone(iana).toFormat('FF'))
    })

    $(document).on('click', '.show-place-change-view-btn', function () {
        let body = tinymce.get('all-editor').getContent(),
            dmNotes
        
        if (isDm) {
            dmNotes = tinymce.get('dm-editor').getContent()
        }
        tinymce.activeEditor.destroy()
        $('.show-place-editor-container:visible').addClass('d-none')
        $('.show-place-body-display:hidden').removeClass('d-none')
        $('#body-content').html(body)
        if (isDm) {
            $('#dm-note-content').html(dmNotes)
        }
    })

    $(document).on('click', '#place-visible', function () {
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
    })
})