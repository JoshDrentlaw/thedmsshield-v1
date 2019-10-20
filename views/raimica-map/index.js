import { state } from './state.js.js.js.js';

$(document).ready(function() {
    $('.marker').popover({
        container: 'body',
        html: true
    });

    // going to need this later
    // let [x, y] = [$(this).offset().top, $(this).offset().left];

    $('#toggle-markers').on('click', function() {
        $('.marker').toggleClass('show-marker');
    });

    $('.marker').on('click', function(e) {
        $('.edit').on('click', function() {
            let id = $(e.target).data('index');
            let note_title = $(this).parents().find('h3').text();
            let note_body = $(this).parent().find('p').text();
            $(this).parents().find('h3').html(`<input type="text" data-id="${id}" class="title-edit popover-header form-control" value="${note_title}">`);
            console.log('title filled in');
            $(this).parent().find('p').replaceWith(`<textarea data-id="${id}" class="edit-box form-control" rows="4">${note_body}</textarea>`);
            $(this).off('click');
            $(this).text('Save').toggleClass('save edit');
        });
        console.log($(this));
        $(this).parents().on('click', '.save', function() {
            let id = $(this).data('id');
            let note_title = $(this).parent().siblings('h3').find('input').val();
            console.log($(this).parent().siblings('h3').find('input'));
            let note_body = $(this).parent().find('textarea').val();
            $.ajax({
                method: 'POST',
                data: {
                    id,
                    note_title,
                    note_body
                },
                success: function(res) {
                    $(`input[data-id=${id}]`).html(note_title);
                    $(`textarea[data-id=${id}]`).replaceWith(`<p class="note-body">${note_body}</p>`);
                    $(`button[data-id=${id}]`).text('Edit');
                }
            });
        });
    });
});