import { state } from './state.js';
console.log(state);

function handleClick(e) {
    console.log(e.currentTarget);
    let note = state.notes[0].note;
    let el = e.currentTarget;
    let elPosition = $(el).offset();
    $(el).after(`<div class="note" style="top: ${elPosition.top - 170}px; left: ${elPosition.left - 75}px">${note}</div>`);
}

$('#map-container').on('click', '#note1', handleClick);