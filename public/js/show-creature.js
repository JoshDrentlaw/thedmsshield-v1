$(document).ready(function () {
    $(document).on('blur', '.show-creature-name', function () {
        let name = $(this).text()
        if (!creature_id) {
            creature_id = $('#creature-id').val()
        }
        axios.put(`/creatures/${creature_id}`, {name})
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
                                <i class="fa fa-map-marker-alt"></i>
                                <small class="text-muted">${mapModel.name}</small>
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
        if (isDm) {
            let creatureId = $this.siblings('#creature-id').val()
            $editorContainer.removeClass('d-none')
            $this.addClass('d-none')
            tinymceInit(creatureId, 'creatures', {selector: '.show-creature-body-editor'})
            let iana = luxon.local().toFormat('z')
            $saveTime.text(luxon.fromISO($saveTime.text()).setZone(iana).toFormat('FF'))
        }
    })

    $(document).on('click', '.show-creature-change-view-btn', function () {
        let body = tinymce.activeEditor.getContent()
        tinymce.activeEditor.destroy()
        $('#show-creature-editor-container').addClass('d-none')
        $('#show-creature-body-display').removeClass('d-none').html(body)
    })
})