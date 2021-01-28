$(document).ready(function () {
    setTimeout(function () {
        Echo.join(`map-chat-message-${map_id}`)
            .here(users => {
                console.log(users)
                showLoggedInUsers(users)
            })
            .joining(user => {
                showLoggedInUser(user)
            })
            .listen('NewMapChatMessage', (e) => {
                console.log('new message!')
                console.log(e.mapChatMessage);
                showMessage(e.mapChatMessage)
            })
            .leaving(user => {
                $(`#user-${user.id}`).remove()
            })
    }, 1000)

    function showLoggedInUsers(users) {
        $('#logged-in-users-container').children().remove()
        users.forEach(u => {
            showLoggedInUser(u)
        })
    }

    function showLoggedInUser(user) {
        let content = `
            <div class="media" id="user-${user.id}">
        `
        if (user.avatar) {
            content += `
                <img src="${CLOUDINARY_IMG_PATH}c_thumb,w_25,h_25/v${luxon.local().valueOf()}/${user.avatar}.jpg" class="mr-3">
            `
        } else {
            content += `
                <div style="width:25px;height:25px;padding:0.25em;" class="img-thumbnail mr-3" id="edit-avatar"><i class="fa fa-user w-100 h-100"></i></div>
            `
        }
        content += `
                <div class="media-body">
                    <h5>${user.username}</h5>
                </div>
            </div>
        `
        if (user.isDm) {
            $('#logged-in-users-container').prepend(content)
        } else {
            $('#logged-in-users-container').append(content)
        }
    }
})