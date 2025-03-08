jQuery(function () {
    let fadeDivs = $('.fade-div');
    fadeDivs.on('mouseenter', function () {
        $(this).css('background-color', 'var(--yellow)');
    });
    fadeDivs.on('mouseleave', function () {
        $(this).css('background-color', 'white');
    });


    $('#fadeDiv1').on('click', function () {
        $('#div1').fadeOut();
    });

    $('#fadeDiv2').on('click', function () {
        $('#div2').fadeOut();
    });

    $('#fadeDiv3').on('click', function () {
        $('#div3').fadeOut();
    });


    $('#datepicker').datepicker();

    $('#accordion').accordion();

    $('#progressbar').progressbar({
        value: 0
    });


    let progressValue = 0;
    const totalTime = 20; // 20 seconds

    const progressInterval = setInterval(function () {
        progressValue += 1;
        $('#progressbar').progressbar({
            value: (progressValue / totalTime) * 100
        });

        if (progressValue >= totalTime) {
            clearInterval(progressInterval);
        }
    }, 1000); // update every 1 second
});