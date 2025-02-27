jQuery(function () {
    $('head').append('<link rel="stylesheet" href="/components/navbar/navbar.css">');
    $('head').append('<script src="https://kit.fontawesome.com/44ecd57f75.js" crossorigin="anonymous"></script>');

    // Toggle mobile menu
    $("#hamburger").on('click', function () {
        $("#nav-items").toggleClass("active");
    });


    // Dropdown and submenu functionality (for desktop)
    $(".dropdown").on('hover', function () {
        $(this).find(".dropmenu").stop(true, true).slideDown();
    }, function () {
        $(this).find(".dropmenu").stop(true, true).slideUp();
    });

    $(".submenu-wrap").on('hover', function () {
        $(this).find(".submenu").stop(true, true).fadeIn();
    }, function () {
        $(this).find(".submenu").stop(true, true).fadeOut();
    });
});