$(document).ready(function () {
    $(document).on('click', '#die-roll-btn', rollDie)

    $(document).on('submit','#die-roll-btn', rollDie)

    function rollDie(e) {
        e.preventDefault()
        let dieAmounts = [], 
            dice = [],
            dieResults = []

        $('.die-roll-group:visible').each(function() {
            let dieAmt = $(this).find('.die-amount').val()
            let die = $(this).find('.die-select').val()
            for (let i = 0; i < dieAmt; i++) {
                let result = Math.floor(Math.random() * die) + 1
                dieResults.push(result)
            }
        });

        /* for (let i = 0; i < dieAmt; i++) {
            let result = Math.floor(Math.random() * die) + 1
            dieResults.push(result)
        } */
        let resultString = dieResults.join(', ')
        $('#first-die-results').val(resultString)
        console.log(dieResults.join(', '))

        axios.post('/mapChatMessages', {
            message:resultString, 
            userId:user_id,
            mapId:map_id
        }).then(res => {
            console.log(res)
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