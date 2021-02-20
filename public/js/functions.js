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

function tinymceInit(id, path, opts) {
    console.log(id)
    let saveTimeout
    let options = {
        height: 500,
        skin_url: '/css/',
        content_css: '/css/content.css',
        plugins: 'autosave',
        autosave_interval: '3s',
        autosave_prefix: '{path}-autosave-{query}',
        autosave_ask_before_unload: false,
        indent: false,
        init_instance_callback: function (editor) {
            console.log(id)
            if (id !== 'new') {
                editor.focus()
                editor.selection.select(editor.getBody(), true)
                editor.selection.collapse(false)
                editor.on('input', function () {
                    if (saveTimeout) {
                        clearTimeout(saveTimeout)
                    }
                    saveTimeout = setTimeout(function () {
                        let body = tinymce.activeEditor.getContent()
                        axios.put(`/${path}/${id}`, {body})
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