$(document).ready(function () {
    $(document).on('click', '#first-die-btn', rollDie)

    $(document).on('submit','#first-die-form', rollDie)

    function rollDie(e) {
        e.preventDefault()
        let firstDieAmt = $('#first-die-amt').val(),
            firstDie = $('#first-die').val(),
            firstDieResults = []

        for (let i = 0; i < firstDieAmt; i++) {
            let result = Math.floor(Math.random() * firstDie) + 1
            firstDieResults.push(result)
        }
        $('#first-die-results').val(firstDieResults.join(', '))
        console.log(firstDieResults.join(', '))
    }
})