function showValidationErrors(errors, model) {
    let text = ''
    for (let err in errors) {
        console.log(`[name="${model}-${err}"]`)
        $(`[name="${model}-${err}"]`).addClass('is-invalid')
        text += `${model} ${err} <ul>`
        errors[err].map(msg => {
            text += `<li>${msg}</li>`
        })
        text += '</ul>'
    }
    pnotify.error({title: 'The following errors occured:', text, textTrusted: true})
}

function tinymceInit(id, path, opts, dm = false) {
    let saveTimeout,
        autosaveId = dm ? 'dm' : 'all'

    let options = {
        height: 300,
        skin_url: '/css/',
        content_css: '/css/content.css',
        plugins: 'autosave',
        autosave_interval: '1s',
        autosave_prefix: `{path}-autosave-${autosaveId}-{query}`,
        autosave_ask_before_unload: false,
        indent: false,
        init_instance_callback: function (editor) {
            if (id !== 'new') {
                editor.focus()
                editor.selection.select(editor.getBody(), true)
                editor.selection.collapse(false)
                editor.on('input', function () {
                    if (saveTimeout) {
                        clearTimeout(saveTimeout)
                    }
                    saveTimeout = setTimeout(function () {
                        let body = tinymce.get('all-editor').getContent(),
                            dmNotes,
                            putData = {body}

                        if (dm) {
                            dmNotes = tinymce.get('dm-editor').getContent()
                            putData['dm_notes'] = dmNotes
                        }

                        if (Object.prototype.hasOwnProperty.call(opts, 'putData')) {
                            putData = $.extend(true, {}, putData, opts.putData)
                        }
                        axios.put(`/${path}/${id}`, putData)
                            .then(function ({ data }) {
                                if (data.status === 200) {
                                    $('.save-time:visible').addClass('shadow-pulse');
                                    $('.save-time:visible').on('animationend', function(){    
                                        $('.save-time:visible').removeClass('shadow-pulse');
                                    });
                                    let iana = luxon.local().toFormat('z')
                                    $('.save-time:visible').text(luxon.fromISO(data.updated_at).setZone(iana).toFormat('FF'))
                                }
                            }) 
                    }, 1000)
                })
            }
        }
    }
    options = $.extend(true, options, opts)
    return tinymce.init(options)
}