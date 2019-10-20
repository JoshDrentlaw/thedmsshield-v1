import { state } from './state.js';

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

    $(document).on('click', '.edit', function() {
        let id = $(this).data('id');
        let note_title = $(this).parents().find('h3').text();
        let note_body = $(this).parent().find('p').text();
        $(this).parents().find('h3').html(`<input type="text" data-id="${id}" class="title-edit form-control" value="${note_title}">`);
        $(this).parent().find('p').replaceWith(`<textarea data-id="${id}" class="edit-box form-control" rows="4">${note_body}</textarea>`);
        $(this).prop('disabled', true);
        $(this).siblings('.save').prop('disabled', false);
    });

    $(document).on('click', '.save', function() {
        let id = $(this).data('id');
        let note_title = $(this).parents('.popover-body').siblings('h3').find('input').val();
        let note_body = $(this).parent().find('textarea').val();
        $.ajax({
            method: 'POST',
            data: {
                id,
                note_title,
                note_body
            },
            success: function(res) {
                $(`input[data-id=${id}]`).parent().html(note_title);
                $(`textarea[data-id=${id}]`).replaceWith(`<p class="note-body">${note_body}</p>`);
                $(`button.save[data-id=${id}]`).prop('disabled', true);
                $(`button.edit[data-id=${id}]`).prop('disabled', false);
            }
        });
    });
});