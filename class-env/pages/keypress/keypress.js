jQuery(function () {
    let vowels = ['a', 'e', 'i', 'o', 'u'];


    $(document).on("keypress", function(event) {  
        const pressedKey = event.key.toLowerCase(); 
        
        if (pressedKey === 'r') {
            $('body').css('background-color', 'indianred');
        } else if (pressedKey === 'g') {
            $('body').css('background-color', 'mediumseagreen');
        } else if (pressedKey === 'b') {
            $('body').css('background-color', 'dodgerblue');
        }
        if(vowels.includes(pressedKey)) {
            $('#lastKey').text("Last Key Pressed: " + event.key.toUpperCase());
        } else
        {
            $('#lastKey').text("Last Key Pressed: " + event.key);
        }
    });
});
