import { state } from './state.js';
$(document).ready(function() {
    console.log(state);
    let displace = window.displacejs

    // Set map markers
    state.points.map((set, i) => {
        $('#map-container')
            .append(`
                <button
                    id="marker${i}"
                    class="marker btn show-marker"
                    data-index="${i}"
                    data-container="body"
                    data-toggle="popover"
                    data-placement="top"
                    data-content="${state.notes[i].note}"
                ></button>
            `);
        $(`#marker${i}`).offset({ top: set[0], left: set[1] }).popover();
        displace($(`#marker${i}`), {
            constrain: true,
            relativeTo: $('#raimica-map')
        });
    });

    // Event listener to make markers draggable
    $('.marker')
        /* .on('mouseenter', function() {
            $(this).addClass('draggable');
        })
        .on('mousedown', function() {
            console.log('down');
            $(this).removeAttr('style');
        }) */
        .on('mouseup', function() {
            console.log('up');
            let i = $(this).data('index');
            let [x, y] = [$(this).offset().top, $(this).offset().left];
            $(this).removeClass('draggable').offset({ top: x, left: y });
        });

    $('#toggle-markers').on('click', function() {
        $('.marker').toggleClass('show-marker');
    });
});