$(document).ready(function () {
    $(document).on('click', '#item-visible', function () {
        const itemId = $('#item-id').val(),
            $this = $(this),
            visible = !$this.hasClass('btn-success')

        axios.put(`/items/${itemId}`, { type: 'visibility', map_id, visible })
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