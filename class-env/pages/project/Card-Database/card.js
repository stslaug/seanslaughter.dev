jQuery(function () {
    // Cache DOM elements
    const $format = $('#format');
    const $commanderGroup = $('#commanderGroup');
    const $cardSearchForm = $('#cardSearchForm');
    const $searchButton = $('#searchButton');
    const $cardViewer = $('#card-viewer tbody');
    const $totalCards = $('#totalCards');

    // Input fields
    const $cardName = $('#cardName');
    const $typeLine = $('#typeLine');
    const $convManaCost = $('#converted-manaCost');

    // Color checkboxes
    const $colorWhite = $('#color-W');
    const $colorBlue = $('#color-U');
    const $colorBlack = $('#color-B');
    const $colorRed = $('#color-R');
    const $colorGreen = $('#color-G');
    const $colorColorless = $('#color-C');
    const $colorsMatchType = $('#color-match-type');


    // Commander color checkboxes
    const $commanderWhite = $('#commander-W');
    const $commanderBlue = $('#commander-U');
    const $commanderBlack = $('#commander-B');
    const $commanderRed = $('#commander-R');
    const $commanderGreen = $('#commander-G');
    const $commanderColorless = $('#commander-C');

    const $setName = $('#setName');

    const $cardPopUp = $('#card-popup');

    // Toggle commander group visibility based on format selection
    $format.on("change", function () {
        const formatValue = $(this).val();
        const commanderFormats = ["commander", "oathbreaker", "brawl", "pauper_commander", "brawl_historic"];

        if (commanderFormats.includes(formatValue)) {
            $commanderGroup.css({
                "opacity": 1, "visibility": "visible"
            });
        } else {
            $commanderGroup.css({
                "opacity": 0, "visibility": "hidden"
            });
        }
    });

    // Helper function to get all selected colors
    function getSelectedColors() {
        const colors = [];
        if ($colorWhite.is(':checked')) colors.push('W');
        if ($colorBlue.is(':checked')) colors.push('U');
        if ($colorBlack.is(':checked')) colors.push('B');
        if ($colorRed.is(':checked')) colors.push('R');
        if ($colorGreen.is(':checked')) colors.push('G');
        if ($colorColorless.is(':checked')) colors.push('C');
        return colors;
    }

    // Helper function to get all selected commander colors
    function getSelectedCommanderColors() {
        const colors = [];
        if ($commanderWhite.is(':checked')) colors.push('W');
        if ($commanderBlue.is(':checked')) colors.push('U');
        if ($commanderBlack.is(':checked')) colors.push('B');
        if ($commanderRed.is(':checked')) colors.push('R');
        if ($commanderGreen.is(':checked')) colors.push('G');
        if ($commanderColorless.is(':checked')) colors.push('C');
        return colors;
    }

    // Builds query parameters for Scryfall API
    function buildQueryParams() {
        const params = {
            action: 'searchCards'
        };

        // Add card name if provided
        if ($cardName.val()) {
            params.cardName = String($cardName.val()).trim();
        }

        // Add type line if provided
        if ($typeLine.val()) {
            let tempType = $typeLine.val().split(' ');
            params.typeLine = tempType;
            console.log(tempType);
        }

        // Add mana cost if provided
        if ($convManaCost.val()) {
            params.convManaCost = String($convManaCost.val()).trim();
        }

        params.legal = String($format.val()).trim();

        // Add colors if selected
        const colors = getSelectedColors();
        if (colors.length > 0) {
            params.colors = colors.join(',');
            console.log("Color Match Type: " + $colorsMatchType.val());
            params.colorsMask = $colorsMatchType.val();
        }

        // Add commander colors if in a commander format and colors selected
        if ($commanderGroup.css('visibility') === 'visible') {
            const commanderColors = getSelectedCommanderColors();
            if (commanderColors.length > 0) {
                params.commanderColors = commanderColors.join(',');
            }
        }
        if ($setName.val()) {
            params.setName = String($setName.val()).trim();
        }

        return params;
    }

    // Renders a card row in the table
    function renderCardRow(card) {
        // Extract color identity and format it
        const colors = card.color_identity && card.color_identity.length > 0 ? card.color_identity.join(',') : 'Colorless';

        // Extract card type components
        const typeLineParts = card.type_line ? card.type_line.split('—').map(part => part.trim()) : ['', ''];
        const superType = typeLineParts[0];
        const subType = typeLineParts.length > 1 ? typeLineParts[1] : '';

        // Create row HTML
        const rowHtml = `
            <tr class="row">
                <td class="col">
                    <button class="btn card-btn" data-card-id="${card.id}">${card.name}</button>
                </td>
             
                <td class="col">${superType} ${subType}</td>
                <td class="col">${colors}</td>
                <td class="col">${card.rarity ? card.rarity.charAt(0).toUpperCase() + card.rarity.slice(1) : 'Unknown'}</td>
                   <td class="col">${card.set.toUpperCase()}</td>
          
               
            </tr>
        `;

        return rowHtml;
    }

    // Display loading state
    function showLoading() {
        $cardViewer.html('<tr><td colspan="6" style="text-align: center;">Loading cards...</td></tr>');
    }

    // Display error message
    function showError(message) {
        $cardViewer.html(`<tr><td colspan="6" style="text-align: center; color: red;">${message}</td></tr>`);
    }

    // Rate limiting for search button
    let timeoutID;
    // Handle form submission
    $cardSearchForm.on('submit', function (event) {
        event.preventDefault();

        // Prevent rapid clicking
        if (timeoutID) {
            return;
        }

        // Disable search button
        $searchButton.attr('disabled', 'disabled');
        showLoading()

        // Build query parameters
        const params = buildQueryParams();

        // Add page parameter (default to page 1)
        params.page = 1;

        // Fetch cards from the API
        $.ajax({
            url: 'card-fetch.php',
            method: 'GET',
            data: params,
            dataType: 'json',
            success: function (response) {
                // Clear table
                $cardViewer.empty();

                if (response.error) {
                    showError("Error: " + response.error);
                } else if (response.data && response.data.length > 0) {
                    // success now render each card
                    response.data.forEach(function (card) {
                        $cardViewer.append(renderCardRow(card));
                    });

                    if (response.total_cards) {
                        console.log(response.total_cards);
                        $totalCards.text("Found " + response.total_cards + " result(s)");

                    }
                    // If we have pagination data, add load more button
                    if (response.has_more) {
                        const nextPage = 2; // First page is 1, next would be 2
                        //Todo Change from table
                        $cardViewer.append(`
                            <tr>
                                <td colspan="6" style="text-align: center;">
                                    <button class="btn load-more" data-page="${nextPage}">Load More</button>
                                </td>
                            </tr>
                        `);
                    }
                } else {
                    $cardViewer.html('<tr><td colspan="6" style="text-align: center;">No cards found matching your criteria.</td></tr>');
                }
            }, error: function (error) {
                showError('Error retrieving cards. Are you sure the card you are looking for exists?');
            }, complete: function () {
                // Enable search button after 2 seconds
                timeoutID = setTimeout(function () {
                    $searchButton.removeAttr('disabled');
                    timeoutID = null;
                }, 5000);
            }
        });
    });

    // Handle load more button click
    $cardViewer.on('click', '.load-more', function () {
        const nextPage = $(this).data('page');
        const params = buildQueryParams();
        params.page = nextPage;

        // Remove the load more button
        $(this).closest('tr').remove();

        // Show loading indicator
        $cardViewer.append('<tr class="loading-more"><td colspan="6" style="text-align: center;">Loading more cards...</td></tr>');

        // Fetch the next page of cards
        $.ajax({
            url: 'card-fetch.php', method: 'GET', data: params, dataType: 'json',
            success: function (response) {
                // Remove loading indicator
                $('.loading-more').remove();

                if (response.error) {
                    showError("Load more: " + response.error);
                } else if (response.data && response.data.length > 0) {
                    // Render each card
                    response.data.forEach(function (card) {
                        $cardViewer.append(renderCardRow(card));
                    });

                    // If we have more pages, add the load more button
                    if (response.has_more) {
                        $cardViewer.append(`
                            <tr>
                                <td colspan="6" style="text-align: center;">
                                    <button class="btn load-more" data-page="${nextPage + 1}">Load More</button>
                                </td>
                            </tr>
                        `);
                    }
                }
            }, error: function (xhr, status, error) {
                $('.loading-more').html(`<td colspan="6" style="text-align: center; color: red;">Error loading more cards: ${error}</td>`);
            }
        });
    });

    // Handle card button click to show card details
    $cardViewer.on('click', '.card-btn', function () {
        const cardId = $(this).data('card-id');

        // Fetch card details
        $.ajax({
            url: 'card-fetch.php', method: 'GET', data: {
                action: 'getCardById', cardId: cardId
            }, dataType: 'json', success: function (card) {
                if (card.error) {
                    alert('Error fetching card details: ' + card.error);
                } else {
                    $cardPopUp.removeClass("hidden");
                    renderCardMenu(card);
                }
            }, error: function (xhr, status, error) {
                alert('Error fetching card details: ' + error);
            }
        });
    });

    // Initial check of format to show/hide commander group
    $format.trigger('change');

    // Generates the HTML for Card Popup
    function renderCardMenu(card) {
        // Using template literals for cleaner HTML building
        const html = `
      <div class="card-container">
        <div class="card-header">
            <button class="btn" id="close">×</button>
        </div>
        
        <div class="card-content">
            <div class="card-primary">
                <div class="card-image">
                    <img src="${card.image_uris.normal}" alt="${card.name}">
                </div>
                
                <div class="card-details">
                    <h2 class="card-name">${card.name}</h2>
                    <p class="card-type">${card.type_line}</p>
                    <p class="card-cmc">CMC: ${card.cmc}</p>
                    <div class="card-text">
                        <p>${card.oracle_text.replace(/\n/g, '<br>')}</p>
                    </div>
                    
                    <div class="card-actions">
                        <a href="${card.purchase_uris.tcgplayer}" target="_blank" class="btn">Buy Card</a>
                    </div>
                </div>
            </div>
            
            <div class="card-legality">
                <h3>Format Legality</h3>
                <div class="legality-grid">
                    ${Object.entries(card.legalities).map(([format, status]) => `
                        <div class="legality-item ${status === 'legal' ? 'legal' : 'not-legal'}">
                            <span class="format-name">${format.charAt(0).toUpperCase() + format.slice(1)}</span>
                            <span class="format-status">${status === 'legal' ? 'Legal' : 'Not Legal'}</span>
                        </div>
                    `).join('')}
                </div>
            </div>
        </div>
    </div>`;


        $cardPopUp.html(html);
        $('#close').on('click', function () {
            $cardPopUp.addClass("hidden");
        });
    }

});