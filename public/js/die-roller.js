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

        $('.die-roll-group:visible').each(function(i) {
            let dieAmt = $(this).find('.die-amount').val(),
                die = $(this).find('.die-select').val(),
                mod = $(this).find('.mod-amount').val(),
                result = numeral(0),
                timestamp = luxon.local().valueOf() * (i + 1),
                resultHtml,
                breakdown = `<span>${mod != 0 ? '(' : ''}`
            for (let i = 0; i < dieAmt; i++) {
                const dieResult = (Math.floor(Math.random() * die)) + 1
                result.add(dieResult)
                if (dieResult == die) {
                    breakdown += `${i > 0 ? ' + ' : ''}<span class=\"text-success\"><strong>${dieResult}</strong></span>`
                } else if (dieResult == 1) {
                    breakdown += `${i > 0 ? ' + ' : ''}<span class=\"text-danger\"><strong>${dieResult}</strong></span>`
                } else {
                    breakdown += `${i > 0 ? ' + ' : ''}${dieResult}`
                }
            }
            breakdown += `${mod != 0 ? `) ${mod > 0 ? '+' : '-'} ${mod}` : ''}</span>`
            resultHtml = `
                <div class="roll-container my-1">
                    <span class="p-1 rounded" >
                        ${result.add(mod).value()} (${dieAmt}d${die}${mod != 0 ? ` ${mod > 0 ? '+' : '-'} ${mod}` : ''})
                    </span>
                    <button class="btn btn-primary btn-sm" type="button" data-toggle="collapse" data-target="#breakdown-${timestamp}" aria-expanded="false" aria-controls="breakdown-${timestamp}">
                        Breakdown
                    </button>
                    <div class="collapse my-2" id="breakdown-${timestamp}">
                        <div class="card card-body">${breakdown}</div>
                    </div>
                </div>
            `
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
        let iana = luxon.local().toFormat('z'),
            img = '',
            breakdown = []

        /* $(message.message).find('.breakdown').each(function () {
            breakdown.push($(this).html())
        }) */

        if (message) {
            img = `<img src="${CLOUDINARY_IMG_PATH}c_thumb,w_25,h_25/v${luxon.local().valueOf()}/${message.user.avatar_public_id}.jpg" alt="User avatar">`
        } else {
            img = `<div style="width:25px;height:25px;padding:0.25em;" class="img-thumbnail mr-3"><i class="fa fa-user w-100 h-100"></i></div>`
        }

        $('#chat-message-list').prepend(`
            <li class="media border rounded mb-4">
                <div class="media-body">
                    <h5 class="my-0 p-2">${message.message}</h5>
                    <div class="media p-2 border-top" style="background:#e9ecef;">
                        <div class="mr-2">${img}</div>
                        <div class="media-body d-flex align-items-center" style="height:25px;">
                            <span>${message.user.username} ${luxon.fromISO(message.created_at).setZone(iana).toFormat('FF')}</span>
                        </div>
                    </div>
                </div>
            </li>
        `)/* .find('[data-toggle="popover"]').each(function (i) {
            $(this).popover({
                html: true,
                trigger: 'hover',
                placement: 'right',
                container: 'body',
                title: 'Breakdown',
                content: breakdown[i]
            })
        }) */
    }
})