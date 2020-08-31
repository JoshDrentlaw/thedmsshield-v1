$(document).ready(function() {
    $('#accept').on('click', function() {
        let id = $(this).data('invite-id')
        axios.post('/dashboard/accept_map_invite', { id })
            .then(res => {
                if (res.status === 200) {
                    PNotify.success({
                        title: 'Invite accepted',
                        text: res.data.msg,
                        delay: 1000
                    })
                    $('#invite-btns').html(`<p class="text-success">Map invite accepted!</p>`)
                }
            })
    })

    $('#deny').on('click', function() {
        let id = $(this).data('invite-id')
        axios.post('/dashboard/deny_map_invite', { id })
    })
})