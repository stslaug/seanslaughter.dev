jQuery(function () {
    $('head').append('<link rel="stylesheet" href="/components/navbar/navbar.css">');

    // Function to check and dynamically load FontAwesome script
    function loadFontAwesome() {
        if (!$('script[src="https://kit.fontawesome.com/44ecd57f75.js"]').length) {
            let script = document.createElement('script');
            script.src = "https://kit.fontawesome.com/44ecd57f75.js";
            script.crossOrigin = "anonymous";
            $('head').append(script);
        }
    }

    loadFontAwesome();

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