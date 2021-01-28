$(document).ready(function () {
    $(document).on('click', '#first-die-btn', rollDie)

    $(document).on('submit','#first-die-form', rollDie)

    function rollDie(e) {
        e.preventDefault()
        let firstDieAmt = $('#first-die-amt').val(),
            firstDie = $('#first-die').val(),
            firstDieResults = []

        for (let i = 0; i < firstDieAmt; i++) {
            let result = Math.floor(Math.random() * firstDie) + 1
            firstDieResults.push(result)
        }
        let resultString = firstDieResults.join(', ')
        $('#first-die-results').val(resultString)
        console.log(firstDieResults.join(', '))

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