$(document).ready(function () {
    $(document).on('click', '#creature-visible', function () {
        const creatureId = $('#creature-id').val(),
            $this = $(this),
            visible = !$this.hasClass('btn-success')

        axios.put(`/creatures/${creatureId}`, { type: 'visibility', map_id, visible })
            .then(res => {
                if (res.status === 200) {
                    $this.toggleClass('btn-danger btn-success')
                    $this.children().remove()
                    let icon
                    if (visible) {
                        icon = 'fa-eye'
                    } else {
                        icon = 'fa-eye-slash'
                    }
                    $this.append(`<i class="fa ${icon}"></i>`)
                }
            })
    })
})