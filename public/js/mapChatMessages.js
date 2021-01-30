$(document).ready(function () {
    campaignMapChannel.listen('NewMapChatMessage', (e) => {
        console.log('new message!')
        console.log(e.mapChatMessage);
        showMessage(e.mapChatMessage)
    })
})