$(document).ready(function () {
    let editor, saveTimeout

    $('#place-name').on('blur', function () {
        axios.put(`/places/${id}`, {name: $(this).text()})
            .then(function ({ data }) {
                if (data.status === 200) {
                    pnotify.success({title: 'Name updated!'})
                    const state = {campaign_id: campaign_id, place_id: id}
                    window.history.pushState(state, '', data.redirect)
                }
            })
    })

    $('#body-display').on('click', function () {
        $(this).addClass('d-none')
        $('#editor-container').removeClass('d-none')
        tinymceInit()
        let iana = luxon.local().toFormat('z')
        $('#save-time').text(luxon.fromISO($('#save-time').text()).setZone(iana).toFormat('FF'))
    })

    function tinymceInit() {
        editor = tinymce.init({
            selector: '#body-editor',
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
                        axios.put(`/places/${id}`, {body})
                            .then(function ({ data }) {
                                if (data.status === 200) {
                                    $('#save-time').addClass('shadow-pulse');
                                    $('#save-time').on('animationend', function(){    
                                        $('#save-time').removeClass('shadow-pulse');
                                        // do something else...
                                    });
                                    let iana = luxon.local().toFormat('z')
                                    $('#save-time').text(luxon.fromISO(data.updated_at).setZone(iana).toFormat('FF'))
                                }
                            }) 
                    }, 1000)
                })
            }
        })
    }

    $('#change-view-btn').on('click', function () {
        let body = tinymce.activeEditor.getContent()
        tinymce.activeEditor.destroy()
        $('#editor-container').addClass('d-none')
        $('#body-display').removeClass('d-none').html(body)
    })
})