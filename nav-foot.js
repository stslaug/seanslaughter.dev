$('head').append('<link crossorigin = "anonymous" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity = "sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" referrerpolicy = "no-referrer" rel = "stylesheet"/>');


jQuery(function () {
    // Dynamically Load the Navbar
    let $head = $('head');
    $head.append('<link rel="stylesheet" href="/components/navbar/navbar.css">'); // These are called before the
    // fetch to ensure the CSS is loaded before the HTML
    $head.append('<link rel="stylesheet" href="/components/footer/footer.css">');
    $.get('/components/navbar/navbar.html', function (data) {
        $('#navbar').html(data);
        checkTheme();

    }).fail(function (error) {
        console.error('Error loading navbar:', error);
    });
    $.get('/components/footer/footer.html', function (data) {
        $('#footer').html(data);
    }).fail(function (error) {
        console.error('Error loading navbar:', error);
    });

    function checkTheme() {
        // Get stored theme preference
        let theme = localStorage.getItem('theme');

        // If no theme is stored, check system preference
        if (!theme) {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            theme = prefersDark ? 'dark' : 'light';
        }

        // Apply theme immediately on page load
        if (theme === 'dark') {
            document.body.classList.add('dark-mode');
        } else {
            document.body.classList.remove('dark-mode');
        }

        // Store the current theme
        localStorage.setItem('theme', theme);
    }


    //
    // if (window.location.hostname === 'localhost') {
    //     // Create a new script element
    //     var liveReloadScript = document.createElement('script');
    //     liveReloadScript.type = 'text/javascript';
    //     liveReloadScript.src = 'https://livejs.com/live.js';
    //     // Append the script to the head section
    //     $('head').append(liveReloadScript);
    //     console.log('Live.js reload script added for local development');
    // }
    //
})