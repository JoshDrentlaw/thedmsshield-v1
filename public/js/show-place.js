$(document).ready(function () {
    let editor, saveTimeout

    $(document).on('blur', '#show-place-name', function () {
        console.log(place_id)
        axios.put(`/places/${place_id}`, {name: $(this).text()})
            .then(function ({ data }) {
                if (data.status === 200) {
                    pnotify.success({title: 'Name updated!'})
                    const state = {campaign_id, place_id}
                    window.history.pushState(state, '', data.redirect)
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

    function tinymceInit() {
        editor = tinymce.init({
            selector: '#show-place-body-editor',
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
                        axios.put(`/places/${place_id}`, {body})
                            .then(function ({ data }) {
                                if (data.status === 200) {
                                    $('#show-place-save-time').addClass('shadow-pulse');
                                    $('#show-place-save-time').on('animationend', function(){    
                                        $('#show-place-save-time').removeClass('shadow-pulse');
                                        // do something else...
                                    });
                                    let iana = luxon.local().toFormat('z')
                                    $('#show-place-save-time').text(luxon.fromISO(data.updated_at).setZone(iana).toFormat('FF'))
                                }
                            }) 
                    }, 1000)
                })
            }
        })
    }

    $(document).on('click', '#show-place-change-view-btn', function () {
        let body = tinymce.activeEditor.getContent()
        tinymce.activeEditor.destroy()
        $('#show-place-editor-container').addClass('d-none')
        $('#show-place-body-display').removeClass('d-none').html(body)
    })
})