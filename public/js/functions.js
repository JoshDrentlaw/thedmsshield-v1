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