jQuery(function () {
    // Toggles the mobile navigation menu
    $('#hamburger').on('click', function (e) {
        e.preventDefault();
        $('#nav-items').toggleClass('active');
    });

    // Handles the back button in mobile dropdowns
    $('.mobile-back').on('click', function (e) {
        e.preventDefault();
        // Clear the URL hash to close the current menu
        window.location.hash = '';
    });

    // Toggle dark mode
    $('#darkmode-toggle').on('click', function () {
        $('body').toggleClass('dark-mode');

        // Switch the icon between moon and sun
        const icon = $(this).find('i');
        if (icon.hasClass('fa-moon')) {
            icon.removeClass('fa-moon').addClass('fa-sun');
        } else {
            icon.removeClass('fa-sun').addClass('fa-moon');
        }

        // Store user preference in localStorage
        const isDarkMode = $('body').hasClass('dark-mode');
        localStorage.setItem('darkMode', isDarkMode);
    });

    // Loads saved dark mode preference
    function loadDarkModePreference() {
        const darkMode = localStorage.getItem('darkMode') === 'true';
        if (darkMode) {
            $('body').addClass('dark-mode');
            $('#darkmode-toggle i').removeClass('fa-moon').addClass('fa-sun');
        }
    }

    // Initialize preferences on page load
    loadDarkModePreference();
});