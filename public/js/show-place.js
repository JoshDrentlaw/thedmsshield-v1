$(document).ready(function () {
    let editor, saveTimeout

    $(document).on('blur', '.show-place-name', function () {
        let name = $(this).text()
        if (!place_id) {
            place_id = $('#place-id').val()
        }
        axios.put(`/places/${place_id}`, {name})
            .then(function ({ data }) {
                if (data.status === 200) {
                    pnotify.success({title: 'Name updated!'})
                    let pathname = document.location.pathname.split('/')
                    pathname = pathname[pathname.length - 2]
                    if (pathname === 'places') {
                        const state = {campaign_id, place_id}
                        window.history.pushState(state, '', data.redirect)
                    } else if (pathname === 'maps') {
                        $(`.marker-list-button[data-place-id="${place_id}"]`).text(name)
                        $(`.compendium-place[data-place-id="${place_id}"]`).html(`
                            ${name}
                            <i class="fa fa-map-marker-alt"></i>
                            <small class="text-muted">${mapModel.name}</small>
                        `)
                    }
                }
            })
    })
    $(document).on('keypress', '.show-place-name', function (e) {
        if (e.key === 'Enter') {
            $('.show-place-name:visible').trigger('blur')
        }
    })

    $(document).on('blur', '.show-place-description', function () {
        let description = $(this).text()
        axios.put(`/places/${place_id}`, {description})
            .then(function ({ data }) {
                if (data.status === 200) {
                    pnotify.success({title: 'Description updated!'})
                    /* let pathname = document.location.pathname.split('/')
                    pathname = pathname[pathname.length - 2]
                    if (pathname === 'places') {
                        const state = {campaign_id, place_id}
                        window.history.pushState(state, '', data.redirect)
                    } else if (pathname === 'maps') {
                        $(`.marker-list-button[data-place-id="${place_id}"]`).text(name)
                        $(`.compendium-place[data-place-id="${place_id}"]`).html(`
                            ${name}
                            <i class="fa fa-map-marker-alt"></i>
                            <small class="text-muted">${mapModel.name}</small>
                        `)
                    } */
                }
            })
    })
    $(document).on('keypress', '.show-place-description', function (e) {
        if (e.key === 'Enter') {
            $('.show-place-description:visible').trigger('blur')
        }
    })

    $(document).on('click', '.show-place-body-display', function () {
        let $this = $(this)
        let $editorContainer = $this.siblings('.show-place-editor-container')
        let $saveTime = $editorContainer.find('.save-time')
        if (isDm) {
            let placeId = $this.siblings('#place-id').val()
            $editorContainer.removeClass('d-none')
            $this.addClass('d-none')
            tinymceInit(placeId, 'places', {selector: '.show-place-body-editor'})
            let iana = luxon.local().toFormat('z')
            $saveTime.text(luxon.fromISO($saveTime.text()).setZone(iana).toFormat('FF'))
        }
    })

    $(document).on('click', '.show-place-change-view-btn', function () {
        let body = tinymce.activeEditor.getContent()
        tinymce.activeEditor.destroy()
        $('.show-place-editor-container:visible').addClass('d-none')
        $('.show-place-body-display:hidden').removeClass('d-none').html(body)
    })
})