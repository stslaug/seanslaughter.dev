jQuery(function () {
    let calculateBtn = $('#calculate-btn');
    let resultsContainer = $('#results-container');
    let errorMessage = $('#error-message');
    let showStepsCheckbox = $('#show-steps');
    let calculationStepsContainer = $('#calculation-steps');

    // Input elements
    let zipcode1Input = $('#zipcode1');
    let zipcode2Input = $('#zipcode2');

    // Result elements
    let resultZip1 = $('#result-zip1');
    let resultZip2 = $('#result-zip2');
    let coordZip1 = $('#result-coord-zip1');
    let coordZip2 = $('#result-coord-zip2');
    let resultDistance = $('#result-distance');

    function isValidZipcode(zipcode) {
        const valid = /^\d{5}$/.test(zipcode);
        console.log('Validating zipcode:', zipcode, 'Result:', valid);
        return valid;
    }

    // Calculate button click handler
    calculateBtn.on('click', function () {
        errorMessage.hide();
        const zip1 = zipcode1Input.val().trim();
        const zip2 = zipcode2Input.val().trim();

        let hasError = false;
        if (zip1 == zip2) {
            setZipError(zipcode1Input);
            setZipError(zipcode2Input);
            hasError = true;
        }

        if (!isValidZipcode(zip1)) {
            setZipError(zipcode1Input);
            hasError = true;
        }

        if (!isValidZipcode(zip2)) {
            hasError = true;
            setZipError(zipcode2Input);
        }

        if (hasError) {
            errorMessage.show();
            resultsContainer.hide();
            calculationStepsContainer.hide();
            return;
        }

        // Show loading state
        calculateBtn.text('Calculating...');
        calculateBtn.prop('disabled', true);

        // Make AJAX request to PHP endpoint
        $.ajax({
            url: 'zipcode.php', type: 'POST', data: {
                zipcode1: zip1, zipcode2: zip2, showSteps: showStepsCheckbox.prop('checked') ? 1 : 0  // Pass checkbox state to server
            }, dataType: 'json', success: function (response) {
                calculateBtn.text('Calculate Distance');
                calculateBtn.prop('disabled', false);

                console.log("AJAX Response:", response);

                if (response.error) {
                    // Show error message
                    errorMessage.text(response.message);
                    errorMessage.show();
                    resultsContainer.hide();
                    calculationStepsContainer.hide();
                } else {
                    // Update the results
                    resultZip1.text(response.from.zipcode + ' (' + response.from.city + ', ' + response.from.state + ')');
                    resultZip2.text(response.to.zipcode + ' (' + response.to.city + ', ' + response.to.state + ')');
                    resultDistance.text(response.distance);
                    coordZip1.text('[ ' + response.from.lng + ', ' + response.from.lat + ' ]');
                    coordZip2.text('[ ' + response.to.lng + ', ' + response.to.lat + ' ]');

                    updateMapPlaceholder(response);

                    resultsContainer.show();
                    displayCalculationSteps(response.calculationSteps);


                }
            }, error: function (xhr, status, error) {
                // Reset button
                calculateBtn.text('Calculate Distance');
                calculateBtn.prop('disabled', false);

                console.log("AJAX Error:", status, error);
                console.log("Response:", xhr.responseText);

                // Show error message
                errorMessage.text('Error connecting to the server. Please try again.');
                errorMessage.show();
                resultsContainer.hide();
                calculationStepsContainer.hide();
            }
        });
    });

    /*
        Generates the content when steps/calculations are enabled
     */
    function generateStepContent(step) {
        let content = '<div class="step-details">';

        if (step.details.formula) {
            content += `<div class="formula"><strong>Formula:</strong> <i>${step.details.formula}</i></div>`;
        }

        for (const [key, value] of Object.entries(step.details)) {
            if (key !== 'formula') {
                let displayValue = value;
                if (typeof value === 'number') { // if a number, format it to 6 digits
                    displayValue = Number.isInteger(value) ? value : value.toFixed(6);
                }
                content += `<div><strong>${key}:</strong> ${displayValue}</div>`;
            }
        }
        content += '</div>';
        return content;
    }

    // Generates content for the calculation steps
    function displayCalculationSteps(calculationSteps) {
        const stepsContainer = $('#calculation-steps-content');
        stepsContainer.empty();

        // Add each step directly to the container
        calculationSteps.forEach((step, index) => {
            const stepElement = $(`
            <div class="calculation-step">
                <h3>${index + 1}. ${step.step}</h3>
                ${generateStepContent(step)}
            </div>
        `);
            stepsContainer.append(stepElement);
        });
        if ($('#show-steps').prop('checked')) {
            showCalculationSteps();
        }
    }

    // IF there is an error with zipcodes each input will get lit up
    function setZipError(zipCodeElement) {
        $(zipCodeElement).css("background-color", "indianred");
        let timeoutId = setTimeout(function () {
            $(zipCodeElement).css("background-color", "");
            timeoutId = null;
        }, 800);
    }

    //Force Turn on of the calculation steps.
    function showCalculationSteps() {
        if (resultsContainer.is(':visible')) {
            calculationStepsContainer.show();
        }
    }

    // Toggles, rather than sets, the calculation steps from displaying.
    function toggleCalculationSteps() {
        if (this.checked) {
            if (resultsContainer.is(':visible')) {
                calculationStepsContainer.show();
            }
        } else {
            calculationStepsContainer.hide();
        }
    }

// Attach the function to the event handler
    showStepsCheckbox.on('change', toggleCalculationSteps);


    /*

     Leaflet Table API Functions
        https://leafletjs.com/examples/quick-start/

     */
    let map;

    /*
    Creates The map and initializes markers/lines for the map
     */
    function createMap(location1, location2) {
        if (map) {
            map.off();
            map.remove();
        }
        map = L.map('map');

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Create markers for both locations
        var marker1 = L.marker([location1.lat, location1.lng]).addTo(map);
        marker1.bindPopup(`<b>${location1.city}, ${location1.state}</b><br>Zipcode: ${location1.zipcode}`);

        var marker2 = L.marker([location2.lat, location2.lng]).addTo(map);
        marker2.bindPopup(`<b>${location2.city}, ${location2.state}</b><br>Zipcode: ${location2.zipcode}`);

        // Create a line between the two points
        var birdsEye = L.polyline([[location1.lat, location1.lng], [location2.lat, location2.lng]], {
            color: 'purple',
            weight: 3,
            opacity: 0.7
        }).addTo(map);

        // Create a bounds object that includes both markers
        var bounds = L.latLngBounds([[location1.lat, location1.lng], [location2.lat, location2.lng]]);

        map.fitBounds(bounds, {padding: [50, 50]});

        return map;
    }

    function updateMapPlaceholder(response) {
        $('#map-placeholder').html('<div id="map" style="height: 300px; width: 100%;"></div>');

        // Create location objects from response
        const location1 = {
            lat: parseFloat(response.from.lat),
            lng: parseFloat(response.from.lng),
            city: response.from.city,
            state: response.from.state,
            zipcode: response.from.zipcode
        };

        const location2 = {
            lat: parseFloat(response.to.lat),
            lng: parseFloat(response.to.lng),
            city: response.to.city,
            state: response.to.state,
            zipcode: response.to.zipcode
        };

        // Initialize map with the locations
        createMap(location1, location2);
    }

});




