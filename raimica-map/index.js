import { state } from './state.js';

$(document).ready(function() {
    $('.marker').popover();

    // Event listener to make markers draggable
    /* $('.marker')
        .on('drag', function() {
            let [x, y] = [$(this).offset().top, $(this).offset().left];
            $(this).css('transform', `translate(1px, 1px)`)
        })
        .on('mouseup', function() {
            console.log('up');
            let i = $(this).data('index');
            let [x, y] = [$(this).offset().top, $(this).offset().left];
            state.points.splice(i, 1, [x, y]);
            console.log(state.points);
        }); */

    $('#toggle-markers').on('click', function() {
        $('.marker').toggleClass('show-marker');
    });
});