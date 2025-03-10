jQuery(function () {
    // global variables to keep track of the request
    // and the function to call when done
    var ajaxreq = false, ajaxCallback;

    // ajaxRequest: Sets up a request
    function ajaxRequest(filename, query) {
        try {
            //make a new request object
            ajaxreq = new XMLHttpRequest();
        } catch (error) {
            console.log('Ajax request error: ', error);
            return false;
        }
        ajaxreq.open("GET", filename + "?query=" + encodeURIComponent(query), true);
        ajaxreq.onreadystatechange = ajaxResponse;
        ajaxreq.send(null);
    }

    // ajaxResponse: Waits for response and calls a function
    function ajaxResponse() {
        if (ajaxreq.readyState != 4) {
            return;
        }
        if (ajaxreq.status == 200) {
            displayResults(ajaxreq.responseXML);
            if (ajaxCallback) ajaxCallback();
        } else alert("Request failed: " + ajaxreq.statusText);
        return true;
    }

    // Set up the callback function to process results
    ajaxCallback = function () {
        // Process the XML response here
        console.log("Response received:", ajaxreq.responseXML);

    };

    function displayResults(xmlData) {
        if (!xmlData) {
            console.error("No XML data received");
            $('#list').html('<p>Error processing results</p>');
            return;
        }

        const states = xmlData.getElementsByTagName('state');
        console.log("Found", states.length, "results");

        if (states.length > 0) {
            // Create table structure
            let html = '<table class ="table">';
            html += '<tr><th>State</th><th>Capital</th></tr>';

            for (let i = 0; i < states.length; i++) {
                const stateName = states[i].getElementsByTagName('name')[0].textContent;
                const stateCapital = states[i].getElementsByTagName('capital')[0].textContent;

                html += '<tr>';
                html += '<td>' + stateName + '</td>';
                html += '<td>' + stateCapital + '</td>';
                html += '</tr>';
            }

            html += '</table>';
            $('#list').html(html);
        } else {
            $('#list').html('<p>No matches found</p>');
        }
    }

    // Correct way to set up the event handler
    $('#searchLive').on('input', function () {
        var query = $(this).val().trim();
        if (query.length > 0) {
            ajaxRequest("search.php", query);  // Changed to .php extension
        } else {
            $('#list').html('<p>[Search results will display here.]</p>');
        }
    });
});