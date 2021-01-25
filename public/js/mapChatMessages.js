$(document).ready(function () {
    setTimeout(function () {
        const testing = { message: 'TESTING sunday', mapId: 2, userId: 1 }

        if (!isDm) {
            axios.post('/mapChatMessages', testing)
                .then((res) => {
                    console.log(res)
                })
        }

        Echo.private(`map-chat-message-2`)
            .listen('NewMapChatMessage', (e) => {
                console.log(e.mapChatMessage);
            })
    }, 1000)
})