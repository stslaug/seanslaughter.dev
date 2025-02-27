jQuery(function() {
    $('body').append('<script type="text/javascript" src="">')
    // Dynamically Load the Navbar
    $.get('/components/navbar/navbar.html', function (data) {
        $('#navbar').html(data);

    }).fail(function (error) {
        console.error('Error loading navbar:', error);
    });
    $.get('/components/footer/footer.html', function (data) {
        $('#footer').html(data);
    }).fail(function (error) {
        console.error('Error loading navbar:', error);
    });

})