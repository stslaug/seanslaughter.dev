jQuery(function () {
    let vowels = ['a', 'e', 'i', 'o', 'u'];


    $(document).on("keypress", function (event) {
        const pressedKey = event.key.toLowerCase();

        if (pressedKey === 'r') {
            let color = $('#red').css('background-color');
            console.log(color);

            $('body').css('background-color', color);
        } else if (pressedKey === 'g') {
            let color = $('#green').css('background-color');
            $('body').css('background-color', color);
        } else if (pressedKey === 'b') {
            let color = $('#blue').css('background-color');
            $('body').css('background-color', color);
        }

        if (vowels.includes(pressedKey)) {
            $('#lastKey').text("Last Key Pressed: " + event.key.toUpperCase());
        } else {
            $('#lastKey').text("Last Key Pressed: " + event.key);
        }
    });
});

