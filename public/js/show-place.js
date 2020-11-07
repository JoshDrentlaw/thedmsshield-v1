$(document).ready(function () {
    let editor, saveTimeout

    $(document).on('blur', '#show-place-name', function () {
        console.log(place_id)
        axios.put(`/places/${place_id}`, {name: $(this).text()})
            .then(function ({ data }) {
                if (data.status === 200) {
                    pnotify.success({title: 'Name updated!'})
                    let pathname = document.location.pathname.split('/')
                    pathname = pathname[pathname.length - 2]
                    if (pathname === 'places') {
                        const state = {campaign_id, place_id}
                        window.history.pushState(state, '', data.redirect)
                    }
                }
            })
    })

    $(document).on('click', '#show-place-body-display', function () {
        console.log('body display')
        if (isDm) {
            $('#show-place-editor-container').removeClass('d-none')
            $(this).addClass('d-none')
            tinymceInit()
            let iana = luxon.local().toFormat('z')
            $('#show-place-save-time').text(luxon.fromISO($('#show-place-save-time').text()).setZone(iana).toFormat('FF'))
        }
    })

    $(document).on('click', '#show-place-change-view-btn', function () {
        let body = tinymce.activeEditor.getContent()
        tinymce.activeEditor.destroy()
        $('#show-place-editor-container').addClass('d-none')
        $('#show-place-body-display').removeClass('d-none').html(body)
    })
})