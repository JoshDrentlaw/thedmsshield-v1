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

    function tinymceInit() {
        editor = tinymce.init({
            selector: '#show-idea-body-editor',
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
                        axios.put(`/ideas/${idea_id}`, {body})
                            .then(function ({ data }) {
                                if (data.status === 200) {
                                    $('#show-idea-save-time').addClass('shadow-pulse');
                                    $('#show-idea-save-time').on('animationend', function(){    
                                        $('#show-idea-save-time').removeClass('shadow-pulse');
                                        // do something else...
                                    });
                                    let iana = luxon.local().toFormat('z')
                                    $('#show-idea-save-time').text(luxon.fromISO(data.updated_at).setZone(iana).toFormat('FF'))
                                }
                            }) 
                    }, 1000)
                })
            }
        })
    }

    $(document).on('click', '#show-idea-change-view-btn', function () {
        let body = tinymce.activeEditor.getContent()
        tinymce.activeEditor.destroy()
        $('#show-idea-editor-container').addClass('d-none')
        $('#show-idea-body-display').removeClass('d-none').html(body)
    })
})