jQuery(function () {
    // Dynamically Load the Navbar
    let $head = $('head');
    $head.append('<link rel="stylesheet" href="/components/navbar/navbar.css">');
    $head.append('<link rel="stylesheet" href="/components/footer/footer.css">');
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