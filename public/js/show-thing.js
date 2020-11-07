$(document).ready(function () {
    let editor, saveTimeout

    $(document).on('blur', '#show-thing-name', function () {
        console.log(thing_id)
        axios.put(`/things/${thing_id}`, {name: $(this).text()})
            .then(function ({ data }) {
                if (data.status === 200) {
                    pnotify.success({title: 'Name updated!'})
                    let pathname = document.location.pathname.split('/')
                    pathname = pathname[pathname.length - 2]
                    if (pathname === 'things') {
                        const state = {campaign_id, thing_id}
                        window.history.pushState(state, '', data.redirect)
                    }
                }
            })
    })

    $(document).on('click', '#show-thing-body-display', function () {
        console.log('body display')
        if (isDm) {
            $('#show-thing-editor-container').removeClass('d-none')
            $(this).addClass('d-none')
            tinymceInit()
            let iana = luxon.local().toFormat('z')
            $('#show-thing-save-time').text(luxon.fromISO($('#show-thing-save-time').text()).setZone(iana).toFormat('FF'))
        }
    })

    $(document).on('click', '#show-thing-change-view-btn', function () {
        let body = tinymce.activeEditor.getContent()
        tinymce.activeEditor.destroy()
        $('#show-thing-editor-container').addClass('d-none')
        $('#show-thing-body-display').removeClass('d-none').html(body)
    })
})