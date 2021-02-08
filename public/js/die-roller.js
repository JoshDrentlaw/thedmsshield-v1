$(document).ready(function () {
    $('.chat-timestamp').each(function () {
        let iana = luxon.local().toFormat('z'),
            time = $(this).text()

        $(this).text(luxon.fromISO(time).setZone(iana).toFormat('FF'))
    })

    $(document).on('click', '#die-roll-btn', rollDie)

    $(document).on('submit','#die-roll-btn', rollDie)

    function rollDie(e) {
        e.preventDefault()
        let msgResults = []

        $('.die-roll-group:visible').each(function() {
            let dieAmt = $(this).find('.die-amount').val(),
                die = $(this).find('.die-select').val(),
                mod = $(this).find('.mod-amount').val(),
                result = numeral(0),
                resultHtml = '<p>',
                breakdown = `<small>(${dieAmt}d${die}${mod != 0 ? ` ${mod > 0 ? '+' : '-'} ${mod}` : ''}) ${mod != 0 ? '[' : ''}`
            for (let i = 0; i < dieAmt; i++) {
                const dieResult = (Math.floor(Math.random() * die)) + 1
                result.add(dieResult)
                if (dieResult == die) {
                    breakdown += `${i > 0 ? ' + ' : ''}<span class="text-success">${dieResult}</span>`
                } else if (dieResult == 1) {
                    breakdown += `${i > 0 ? ' + ' : ''}<span class="text-danger">${dieResult}</span>`
                } else {
                    breakdown += `${i > 0 ? ' + ' : ''}${dieResult}`
                }
            }
            breakdown += `${mod != 0 ? `] ${mod > 0 ? '+' : '-'} ${mod}` : ''}</small>`
            resultHtml += `${result.add(mod).value()} ${breakdown}</p>`
            msgResults.push(resultHtml)
        })

        axios.post('/mapChatMessages', {
            message: msgResults.join(''), 
            userId: user_id,
            mapId: map_id
        }).then(res => {
            showMessage(res.data.mapChatMessage)
        })
    }

    showMessage = function (message) {
        let iana = luxon.local().toFormat('z')
        $('#chat-message-list').prepend(`
            <li class="media">
                <div class="media-body">
                    <h5 class="mt-0 mb-1">${message.message}</h5>
                    <p>${message.user.username} ${luxon.fromISO(message.created_at).setZone(iana).toFormat('FF')}</p>
                </div>
            </li>
        `)
    }
})