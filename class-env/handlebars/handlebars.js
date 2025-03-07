jQuery(document).ready(function () {
    /*
 https://learnwebcode.github.io/json-example/pets-data.json
     */
    let getBtn = $('#getBtn');
    let info = $('#animal-info');
    let pageCounter = 1;

    getBtn.on('click', function () {
        getBtn.fadeOut();
        let request = new XMLHttpRequest();
        request.open("GET", "https://learnwebcode.github.io/json-example/pets-data.json");
        request.onload = function () {
            if (request.status >= 200 && request.status < 400) {
                let data = JSON.parse(request.responseText);
                renderHTML(data);
            } else {
                console.log("Connection Successful but grabbing data has failed: " + request.status);
            }
        }
        request.onerror = function () {
            console.log("Connection Error: " + request.status);
        }
        request.send();

        Handlebars.registerHelper('calculateAge', function (birthYear) {
            let age = new Date().getFullYear() - birthYear;
            if (age > 0) return age + " years old";
            else return "Less than one year old";
        })

        function renderHTML(data) {
            let rawTemplate = document.getElementById('petsTemplate').innerHTML;
            let compiledTemplate = Handlebars.compile(rawTemplate);
            let generatedHTML = $(compiledTemplate(data)); // Wrap in jQuery object for fade effect

            generatedHTML.hide();
            $(".page-container").addClass("contentReady");
            info.append(generatedHTML);

            generatedHTML.fadeIn(500);
        }
    })


});