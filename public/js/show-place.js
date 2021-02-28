$(document).ready(function () {
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
                        let html = name
                        if ($('#marker-id').length) {
                            html += `
                                <i class="fa fa-map-marker-alt"></i>
                                <small class="text-muted">${mapModel.name}</small>
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