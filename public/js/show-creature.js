$(document).ready(function () {
    let editor, saveTimeout

    $(document).on('blur', '#show-creature-name', function () {
        console.log(creature_id)
        axios.put(`/creatures/${creature_id}`, {name: $(this).text()})
            .then(function ({ data }) {
                if (data.status === 200) {
                    pnotify.success({title: 'Name updated!'})
                    let pathname = document.location.pathname.split('/')
                    pathname = pathname[pathname.length - 2]
                    if (pathname === 'creatures') {
                        const state = {campaign_id, creature_id}
                        window.history.pushState(state, '', data.redirect)
                    }
                }
            })
    })

    $(document).on('click', '#show-creature-body-display', function () {
        console.log('body display')
        if (isDm) {
            $('#show-creature-editor-container').removeClass('d-none')
            $(this).addClass('d-none')
            tinymceInit()
            let iana = luxon.local().toFormat('z')
            $('#show-creature-save-time').text(luxon.fromISO($('#show-creature-save-time').text()).setZone(iana).toFormat('FF'))
        }
    })

    $(document).on('click', '#show-creature-change-view-btn', function () {
        let body = tinymce.activeEditor.getContent()
        tinymce.activeEditor.destroy()
        $('#show-creature-editor-container').addClass('d-none')
        $('#show-creature-body-display').removeClass('d-none').html(body)
    })
})