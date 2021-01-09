$(document).ready(function () {
    let checkedSequence = []

    $(document).on('click', '#accept', function() {
        let id = $(this).data('invite-id')
        axios.post('/dashboard/accept_map_invite', { id })
            .then(res => {
                if (res.status === 200) {
                    pnotify.success({
                        title: 'Invite accepted',
                        text: res.data.msg,
                        delay: 1000
                    })
                    $('#invite-btns').html(`<p class="text-success">Map invite accepted!</p>`)
                }
            })
    })

    $(document).on('click', '#deny', function() {
        let id = $(this).data('invite-id')
        axios.post('/dashboard/deny_map_invite', { id })
    })

    $(document).on('click', '#dashboard-message-mark-read', function () {
        let unread = checkedSequence.filter(c => {
            if (!c.read) {
                return c.id
            }
        })
        axios.post('/messages/mark_message_read', { unread })
            .then(res => {
                if (res.status === 200) {
                    checkedSequence = checkedSequence.filter(c => {
                        if (!c.read) {
                            c.read = 1
                            $(`[data-id="${c.id}"]`).siblings('[data-fa-i2svg]').removeClass('fa-envelope').addClass('fa-envelope-open-text')
                        }
                        return c
                    })
                    $('.message-select').trigger('change')
                }
            })
    })

    $(document).on('click', '#dashboard-message-mark-unread', function () {
        let read = checkedSequence.filter(c => {
            if (c.read) {
                return c.id
            }
        })
        axios.post('/messages/mark_message_unread', { read })
            .then(res => {
                if (res.status === 200) {
                    checkedSequence = checkedSequence.filter(c => {
                        if (c.read) {
                            c.read = 0
                            $(`[data-id="${c.id}"]`).siblings('[data-fa-i2svg]').removeClass('fa-envelope-open-text').addClass('fa-envelope')
                        }
                        return c
                    })
                    $('.message-select').trigger('change')
                }
            })
    })

    $(document).on('change', '.message-select', function () {
        if ($('.message-select:checked').length > 0) {
            $('.check-btn').prop('disabled', false)
        } else {
            $('#dashboard-message-mark-unread').replaceWith(`
                <button id="dashboard-message-mark-read" class="btn btn-outline-secondary check-btn">Mark read</button>
            `)
        }

        $('.message-select:checked').each(function () {
            let included = checkedSequence.find(c => c.id === $(this).data('id'))
            if (!included) {
                checkedSequence.push({id:  $(this).data('id'), read: $(this).data('message-read')})
            }
        })

        $('.message-select:not(:checked)').each(function () {
            let idx,
                included = checkedSequence.find((c, i) => {
                if (c.id === $(this).data('id')) {
                    idx = i
                    return c
                }
            })
            if (included) {
                checkedSequence.splice(idx, 1)
            }
        })

        if (checkedSequence.length > 0 && checkedSequence[0].read) {
            $('#dashboard-message-mark-read').replaceWith(`
                <button id="dashboard-message-mark-unread" class="btn btn-outline-secondary check-btn">Mark unread</button>
            `)
        } else {
            $('#dashboard-message-mark-unread').replaceWith(`
                <button id="dashboard-message-mark-read" class="btn btn-outline-secondary check-btn">Mark read</button>
            `)
        }
    })
})