$(document).ready(function () {
    let editor, saveTimeout

    $(document).on('blur', '#show-idea-name', function () {
        console.log(idea_id)
        axios.put(`/ideas/${idea_id}`, {name: $(this).text()})
            .then(function ({ data }) {
                if (data.status === 200) {
                    pnotify.success({title: 'Name updated!'})
                    let pathname = document.location.pathname.split('/')
                    pathname = pathname[pathname.length - 2]
                    if (pathname === 'ideas') {
                        const state = {campaign_id, idea_id}
                        window.history.pushState(state, '', data.redirect)
                    }
                }
            })
    })

    $(document).on('click', '#show-idea-body-display', function () {
        console.log('body display')
        if (isDm) {
            $('#show-idea-editor-container').removeClass('d-none')
            $(this).addClass('d-none')
            tinymceInit()
            let iana = luxon.local().toFormat('z')
            $('#show-idea-save-time').text(luxon.fromISO($('#show-idea-save-time').text()).setZone(iana).toFormat('FF'))
        }
    })

    $(document).on('click', '#show-idea-change-view-btn', function () {
        let body = tinymce.activeEditor.getContent()
        tinymce.activeEditor.destroy()
        $('#show-idea-editor-container').addClass('d-none')
        $('#show-idea-body-display').removeClass('d-none').html(body)
    })
})