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
        let note_title = $(`span[data-id=${id}]`).text();
        let note_body = $(`p[data-id=${id}]`).text();

        $(`span[data-id=${id}]`).replaceWith(`<input type="text" data-id="${id}" class="title-edit form-control" value="${note_title}">`);
        $(`p[data-id=${id}]`).replaceWith(`<textarea data-id="${id}" class="edit-box form-control" rows="4">${note_body}</textarea>`);
        $(this).prop('disabled', true);
        $(this).siblings('.save').prop('disabled', false);
    });

    $(document).on('click', '.save', function() {
        let id = $(this).data('id');
        let note_title = $(`input[data-id=${id}]`).val();
        let note_body = $(`textarea[data-id=${id}]`).val();
        $.ajax({
            method: 'POST',
            data: {
                id,
                note_title,
                note_body
            },
            success: function(res) {
                $(`input[data-id=${id}]`).replaceWith(`<span data-id="${id}" class="note-title">${note_title}</span>`);
                $(`textarea[data-id=${id}]`).replaceWith(`<p data-id="${id}" class="note-body">${note_body}</p>`);
                $(`button.save[data-id=${id}]`).prop('disabled', true);
                $(`button.edit[data-id=${id}]`).prop('disabled', false);
                $(`#marker${id}`).on('hidden.bs.popover', function() {
                    $(this).popover('dispose').popover({
                        container: 'body',
                        toggle: 'popover',
                        html: true,
                        title: `<span data-id="${id}" class="note-title">${note_title}</span>`,
                        content: `
                            <p data-id="${id}" class="note-body">${note_body}</p>
                            <button data-id="${id}" class="edit btn btn-primary btn-sm">Edit</button>
                            <button data-id="${id}" class="save btn btn-primary btn-sm" disabled>Save</button>
                        `
                    });
                });
                    /* .attr('data-title', `<span data-id="${id}" class="note-title">${note_title}</span>`)
                    .attr('data-content', `
                        <p data-id="${id}" class="note-body">${note_body}</p>
                        <button data-id="${id}" class="edit btn btn-primary btn-sm">Edit</button>
                        <button data-id="${id}" class="save btn btn-primary btn-sm" disabled>Save</button>
                    `); */
            }
        });
    });

    $('#raimica-map').on('click', function(e) {
        console.log(e)
        console.log('top:', ((e.offsetY-12)/666.781)*100, 'left:', ((e.offsetX-12)/1024)*100);
    });

    /* $('#add-marker').on('click', function() {
        let current = $('#raimica-map').data('click');
        $('#raimica-map').data('click', !current);
        if ($('#raimica-map').data('click')) {
            $('#raimica-map').on('click', addMarker);
        }
        else {
            console.log('turn off click');
            $('#raimica-map').off('click');
        }
    }); */


    function addMarker() {
        console.log('map click');
    }
});