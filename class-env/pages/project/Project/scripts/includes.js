/*
           This file is to help include each of out navbars and scripts uniformly without having to change them all.
           Just change to your accurate directory
           Also ensures we have the same scripts loaded in the correct order.. (Including CSS files).
*/
jQuery(function () {

    let wilson = 0;
    let tyler = 1;
    let choice = tyler;
    let style_css, u_style_css, navbar, bootstrap_css, subnav;

    if (choice == wilson) {
        style_css = "/class-env/pages/project/Project/css/style.css";   /* CHANGE HERE */
        u_style_css = "/class-env/pages/project/Project/css/u_style.css"; /* CHANGE HERE */
        navbar = "/navbar.html";
        subnav = "/class-env/pages/project/Project/components/subnav.php"

    } else {
        style_css = "/class-env/pages/project/Project/css/style.css";
        u_style_css = "/class-env/pages/project/Project/css/u_style.css";
        navbar = "/components/navbar/navbar.html";
        subnav = "/class-env/pages/project/Project/components/subnav.php";

    }

    /*

        CSS Files
     */
    const styleLink1 = document.createElement('link');
    styleLink1.rel = 'stylesheet';
    styleLink1.href = style_css;
    document.head.appendChild(styleLink1);

    const styleLink2 = document.createElement('link');
    styleLink2.rel = 'stylesheet';
    styleLink2.href = u_style_css;
    document.head.appendChild(styleLink2);

    const bootstrapLink = document.createElement('link');
    bootstrapLink.rel = 'stylesheet';
    bootstrapLink.href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css';
    document.head.appendChild(bootstrapLink);


    /*
        Scripts
     */
    const bootstrapScript = document.createElement('script');
    bootstrapScript.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js';
    document.body.appendChild(bootstrapScript);


    /* Font Awesome ^/


    /*

        NavBar

        Edit here!

     */

    if (choice == wilson) { /* !!!!!!!!!!!!!!!!   YOU MAY HAVE TO EDIT HERE   !!!!!!!!!!!!!!!!!!!! */
        fetch('/navbar.html')
            .then(response => response.text())
            .then(data => {
                document.getElementById('navbar-container').innerHTML = data;
            });


        fetch(navbar) /* Set sub-navbar here */
            .then(response => response.text())
            .then(data => {
                document.getElementById('navbar-container').innerHTML = data;
            });
    } else if (choice == 1) {
        $('head').append('<link crossorigin = "anonymous" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity = "sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" referrerpolicy = "no-referrer" rel = "stylesheet"/>');
        let $head = $('head');
        $head.append('<link rel="stylesheet" href="/components/navbar/navbar.css">');
        $.get(navbar, function (data) {
            let navbarEl = document.getElementById('navbar') || document.getElementById('navbar-container');
            let $navbar = $(navbarEl);

            $navbar.html(data);

        }).fail(function (error) {
            console.error('Error loading navbar:', error);
        });
        fetch(subnav)
            .then(response => response.text())
            .then(data => {
                document.getElementById('game-nav').innerHTML = data;
            });
    }


});