jQuery(function () {
    // Font Awesome setup
    $('head').append('<script src="https://kit.fontawesome.com/44ecd57f75.js" crossorigin="anonymous"></script>');

    // Toggle mobile menu
    $("#hamburger").on('click', function () {
        $("#nav-items").toggleClass("active");
    });

    // Dropdown and submenu functionality (for desktop)
    $(".dropdown").hover(function () {
        $(this).find(".dropmenu").stop(true, true).slideDown();
    }, function () {
        $(this).find(".dropmenu").stop(true, true).slideUp();
    });

    $(".submenu-wrap").hover(function () {
        $(this).find(".submenu").stop(true, true).fadeIn();
    }, function () {
        $(this).find(".submenu").stop(true, true).fadeOut();
    });

    // Apply dark mode from saved preference
    if (localStorage.getItem('darkMode') === 'true') {
        $('body').addClass('dark-mode');
        $('#darkmode-toggle i').removeClass('fa-moon').addClass('fa-sun');
    }

    // Dark mode toggle with improved selector targeting
    $('#darkmode-toggle').on('click', function () {
        $('body, #footer').toggleClass('dark-mode');
        $('#darkmode-toggle i').toggleClass('fa-moon fa-sun');

        // Save preference
        try {
            localStorage.setItem('darkMode', $('body').hasClass('dark-mode') ? 'true' : 'false');
        } catch (e) {
            console.warn('Could not save dark mode preference');
        }
    });
});