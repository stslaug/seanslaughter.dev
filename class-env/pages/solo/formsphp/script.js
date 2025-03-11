jQuery(function () {
    // Cache jQuery objects for form elements
    let season = $('#season');
    let livEnv = $('#livEnv');
    let activity = $('#activity');
    let relax = $('#relax');  // Changed from datepicker to match the form
    let food = $('#food');
    let song = $('#song');
    let artist = $('#artist');
    let fun = $('#fun');
    let color = $('#color');
    let other = $('#other');
    let getDataBtn = $('#getDataBtn');
    let clearBtn = $('#clearBtn');


    getDataBtn.on('click', function (event) {
        event.preventDefault();
        $.ajax({
            url: 'get_data.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                // Use proper case for the response keys to match our PHP output
                season.val(response.season || '');
                livEnv.val(response.livEnv || '');
                activity.val(response.activity || '');
                relax.val(response.relax || '');
                food.val(response.food || '');
                song.val(response.song || '');
                artist.val(response.artist || '');
                fun.val(response.fun || '');
                color.val(response.color || '#000000');
                other.val(response.other || '');
            },
            error: function (xhr, status, error) {
                console.error("Error fetching data:", error);
                alert("Could not load data. Please try again later.");
            }
        });
    });

    clearBtn.on('click', function () {
        document.getElementById("dataForm").reset();
    })


});