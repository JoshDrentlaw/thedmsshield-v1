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

    $('.marker').on('click', function(e) {
        $('.edit').on('click', function() {
            let title = $(this).parents().find('h3').text();
            let body = $(this).parent().find('p').text();
            $(this).parents().find('h3').html(`<input type="text" class="title-edit form-control" value="${title}">`)
            $(this).parent().find('p').replaceWith(`<textarea class="edit-box form-control" rows="4">${body}</textarea>`);
            $(this).toggleClass('invisible');
            $(this).siblings('.save').toggleClass('invisible');
        });

        $('.save').on('click', function() {
            let index = $(e.target).data('index');
            let title = $(this).parents().find('.title-edit').val();
            let note_body = $(this).parent().find('textarea').val();
            $.ajax({
                method: 'POST',
                data: {
                    index,
                    title,
                    note_body
                }
            });
            $(this).parents().find('h3').html(`${title}`);
            $(this).parent().find('.edit-box').replaceWith(`<p class="note-body">${body}</p>`);
            $(this).toggleClass('invisible');
            $(this).siblings('.edit').toggleClass('invisible');
        });
    });
});