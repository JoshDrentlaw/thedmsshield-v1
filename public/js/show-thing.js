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

    function tinymceInit() {
        editor = tinymce.init({
            selector: '#show-thing-body-editor',
            height: 500,
            skin_url: '/css/',
            content_css: '/css/content.css',
            plugins: 'autosave',
            autosave_interval: '3s',
            autosave_prefix: '{path}-autosave-{query}',
            autosave_ask_before_unload: false,
            indent: false,
            init_instance_callback: function (editor) {
                editor.on('input', function () {
                    if (saveTimeout) {
                        clearTimeout(saveTimeout)
                    }
                    saveTimeout = setTimeout(function () {
                        let body = tinymce.activeEditor.getContent()
                        axios.put(`/things/${thing_id}`, {body})
                            .then(function ({ data }) {
                                if (data.status === 200) {
                                    $('#show-thing-save-time').addClass('shadow-pulse');
                                    $('#show-thing-save-time').on('animationend', function(){    
                                        $('#show-thing-save-time').removeClass('shadow-pulse');
                                        // do something else...
                                    });
                                    let iana = luxon.local().toFormat('z')
                                    $('#show-thing-save-time').text(luxon.fromISO(data.updated_at).setZone(iana).toFormat('FF'))
                                }
                            }) 
                    }, 1000)
                })
            }
        })
    }

    $(document).on('click', '#show-thing-change-view-btn', function () {
        let body = tinymce.activeEditor.getContent()
        tinymce.activeEditor.destroy()
        $('#show-thing-editor-container').addClass('d-none')
        $('#show-thing-body-display').removeClass('d-none').html(body)
    })
})