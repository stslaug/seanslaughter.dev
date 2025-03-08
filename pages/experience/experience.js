jQuery(function () {
    let theme = $('#theme');

    $('#darkMode-btn').on('click', function () {
        console.log("Jquery Working");
        $('body').toggleClass("dark-mode");
        $('a').toggleClass("dark-mode");
        $('strong').toggleClass("dark-mode");
        $('h1').toggleClass("dark-mode");
        $('h2').toggleClass("dark-mode");
        $('h3').toggleClass("dark-mode");
        theme.toggleClass('fa-moon');
        theme.toggleClass('fa-sun');

    });
});