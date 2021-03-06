$(document).ready(function () {
    $(document).on('blur', '.show-creature-name', function () {
        let name = $(this).text()
        if (!creature_id) {
            creature_id = $('#creature-id').val()
        }
        axios.put(`/creatures/${creature_id}`, {type: 'edit', name})
            .then(function ({ data }) {
                if (data.status === 200) {
                    pnotify.success({title: 'Name updated!'})
                    let pathname = document.location.pathname.split('/')
                    pathname = pathname[pathname.length - 2]
                    if (pathname === 'creatures') {
                        const state = {campaign_id, creature_id}
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
                                <button class="btn btn-success btn-sm float-right to-marker-btn" data-creature-id="${creature_id}"><i class="fa fa-map-marker-alt"></i></button>
                            `
                        }
                        $(`.compendium-creature[data-creature-id="${creature_id}"]`).html(html)
                    }
                }
            })
    })
    $(document).on('keypress', '.show-creature-name', function (e) {
        if (e.key === 'Enter') {
            $('.show-creature-name:visible').trigger('blur')
        }
    })

    $(document).on('click', '.show-creature-body-display', function () {
        let $this = $(this)
        let $editorContainer = $this.siblings('.show-creature-editor-container')
        let $saveTime = $editorContainer.find('.save-time')
        let creatureId = $this.siblings('#creature-id').val()
        $editorContainer.removeClass('d-none')
        $this.addClass('d-none')
        tinymceInit(creatureId, 'creatures', {selector: '.show-creature-body-editor', putData: {type: 'edit'}})
        if (isDm) {
            tinymceInit(creatureId, 'creatures', { selector: '.show-creature-dm-note-editor', putData: { type: 'edit' } }, true)
        }
        let iana = luxon.local().toFormat('z')
        $saveTime.text(luxon.fromISO($saveTime.text()).setZone(iana).toFormat('FF'))
    })

    $(document).on('click', '.show-creature-change-view-btn', function () {
        let body = tinymce.get('all-editor').getContent(),
            dmNotes
        
        if (isDm) {
            dmNotes = tinymce.get('dm-editor').getContent()
        }
        tinymce.activeEditor.destroy()
        $('.show-creature-editor-container:visible').addClass('d-none')
        $('.show-creature-body-display:hidden').removeClass('d-none')
        $('#body-content').html(body)
        if (isDm) {
            $('#dm-note-content').html(dmNotes)
        }
    })

    $(document).on('click', '#creature-visible', function () {
        const creatureId = $('#creature-id').val(),
            $this = $(this)
            visible = !$this.hasClass('btn-success')

        axios.put(`/creatures/${creatureId}`, { type: 'visibility', map_id, visible })
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