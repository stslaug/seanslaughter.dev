jQuery(function () {
    // Fetch statistics
    async function fetchStatistics() {
        const statsContainer = document.getElementById('stats-container');
        statsContainer.innerHTML = '<p>Loading statistics...</p>';

        try {
            const [sets, totalCards, artists, words, cardTypes, artifactTypes, battleTypes, creatureTypes, enchantmentTypes, landTypes, planeswalkerTypes] = await Promise.all([
                fetch('https://api.scryfall.com/sets').then(res => res.json()),
                fetch('https://api.scryfall.com/catalog/card-names').then(res => res.json()),
                fetch('https://api.scryfall.com/catalog/artist-names').then(res => res.json()),
                fetch('https://api.scryfall.com/catalog/word-bank').then(res => res.json()),
                fetch('https://api.scryfall.com/catalog/card-types').then(res => res.json()),
                fetch('https://api.scryfall.com/catalog/artifact-types').then(res => res.json()),
                fetch('https://api.scryfall.com/catalog/battle-types').then(res => res.json()),
                fetch('https://api.scryfall.com/catalog/creature-types').then(res => res.json()),
                fetch('https://api.scryfall.com/catalog/enchantment-types').then(res => res.json()),
                fetch('https://api.scryfall.com/catalog/land-types').then(res => res.json()),
                fetch('https://api.scryfall.com/catalog/planeswalker-types').then(res => res.json())
            ]);

            // Process data and render statistics
            displayStatistics(statsContainer, sets, totalCards, artists, words, cardTypes, artifactTypes, battleTypes, creatureTypes, enchantmentTypes, landTypes, planeswalkerTypes);
        } catch (error) {
            statsContainer.innerHTML = `<p>Error loading statistics: ${error.message}</p>`;
            console.error('Error fetching statistics:', error);
        }
    }

    //Doesn't generate, but displays the statistics
    function displayStatistics(container, sets, totalCards, artists, words, cardTypes, artifactTypes, battleTypes, creatureTypes, enchantmentTypes, landTypes, planeswalkerTypes, spellTypes) {

        // Build statistics HTML
        const html = `
            <div class="stats-grid">
                <div class="stat-card noptr">
                    <h3>Total Cards</h3>
                    <p class="stat-number">${totalCards.data.length.toLocaleString()}</p>
                </div>
                <div id="sets" class="stat-card">
                    <h3>Total Sets</h3>
                    <p class="stat-number">${sets.data.length.toLocaleString()}</p>
                </div>
                <div id="artists" class="stat-card">
                    <h3>Card Artists</h3>
                    <p class="stat-number">${artists.data.length.toLocaleString()}</p>
                </div>
                <div id="cardTypes" class="stat-card">
                    <h3>Card Types</h3>
                    <p class="stat-number">${cardTypes.data.length.toLocaleString()}</p>
                </div>

             </div>
             <hr>
            <div id="subcategories" class="stats-grid">
                  <!-- Submenus, if any, will be put here -->
            </div>
            <div class="additional-stats">
  
                <div id="stats-display">
                    <!-- Sets timeline will be rendered here -->
                </div>
                
             
            </div>
    `;
        container.innerHTML = html;
        // Handle the sets box on click
        $('#sets').on('click', function () {
            const display = document.getElementById('stats-display');
            const $sub = $(document.getElementById('subcategories'));
            $sub.css({'max-height': '0', 'opacity': '0'});
            setTimeout($sub.html(''), 3000)

            display.innerHTML = '';
            renderSetsTimeline(sets.data);
        });
        $('#artists').on('click', function () {
            const display = document.getElementById('stats-display');
            const $sub = $(document.getElementById('subcategories'));
            $sub.css({'max-height': '0', 'opacity': '0'});
            setTimeout($sub.html(''), 3000)

            display.innerHTML = '';
            renderListData(artists.data);
        });
        $('#cardTypes').on('click', function () {
            renderCardTypesFilters(artifactTypes, battleTypes, creatureTypes, enchantmentTypes, landTypes, planeswalkerTypes);
        });

    }

    // Submenu (Used to display Card type details)
    function renderCardTypesFilters(artifactTypes, battleTypes, creatureTypes, enchantmentTypes, landTypes, planeswalkerTypes) {
        const $sub = $(document.getElementById('subcategories'));
        const display = document.getElementById('stats-display');
        $sub.fadeIn(2000);
        display.innerHTML = '';
        $sub.html(`
                <div id="creatureTypes" class="stat-card">
                    <h3>Creature Types</h3>
                    <p class="stat-number">${creatureTypes.data.length.toLocaleString()}</p>
                </div>
                <div id="artifactTypes" class="stat-card">
                    <h3>Artifact Types</h3>
                    <p class="stat-number">${artifactTypes.data.length.toLocaleString()}</p>
                </div>
                <div id="battleTypes" class="stat-card">
                    <h3>Battle Types</h3>
                    <p class="stat-number">${battleTypes.data.length.toLocaleString()}</p>
                </div>
                <div id="enchantmentTypes" class="stat-card">
                    <h3>Enchantment Types</h3>
                    <p class="stat-number">${enchantmentTypes.data.length.toLocaleString()}</p>
                </div>
                <div id="landTypes" class="stat-card">
                    <h3>Land Types</h3>
                    <p class="stat-number">${landTypes.data.length.toLocaleString()}</p>
                </div>
                <div id="planeswalkerTypes" class="stat-card">
                    <h3>Planeswalker Types</h3>
                    <p class="stat-number">${planeswalkerTypes.data.length.toLocaleString()}</p>
                </div>
            `);

        $sub.css({'max-height': '100%', 'opacity': '1'});

        /*
         *  Creature Types Click Function
         *
         */
        // Creature Types Click Function (already provided)
        $('#creatureTypes').on('click', function () {
            // Clear the display first
            $('#stats-display').empty();

            $.ajax({
                url: 'stat-fetch.php',
                method: 'GET',
                data: {
                    action: 'catalog',
                    type: 'creature-types'
                },
                dataType: 'json',
                success: function (response) {
                    if (response.data && response.data.length > 0) {
                        // Call renderListData once with the entire array
                        renderListData(response.data);
                    } else {
                        $('#stats-display').html("<p>No creature types data available</p>");
                    }
                },
                error: function (error) {
                    $('#stats-display').html("<p>Error loading creature types: " + error.statusText + "</p>");
                    console.log("Error:", error);
                }
            });
        });

        // Artifact Types Click Function
        $('#artifactTypes').on('click', function () {
            // Clear the display first
            $('#stats-display').empty();

            $.ajax({
                url: 'stat-fetch.php',
                method: 'GET',
                data: {
                    action: 'catalog',
                    type: 'artifact-types'
                },
                dataType: 'json',
                success: function (response) {
                    if (response.data && response.data.length > 0) {
                        renderListData(response.data);
                    } else {
                        $('#stats-display').html("<p>No artifact types data available</p>");
                    }
                },
                error: function (error) {
                    $('#stats-display').html("<p>Error loading artifact types: " + error.statusText + "</p>");
                    console.log("Error:", error);
                }
            });
        });

        // Battle Types Click Function
        $('#battleTypes').on('click', function () {
            // Clear the display first
            $('#stats-display').empty();

            $.ajax({
                url: 'stat-fetch.php',
                method: 'GET',
                data: {
                    action: 'catalog',
                    type: 'battle-types'

                },
                dataType: 'json',
                success: function (response) {
                    if (response.data && response.data.length > 0) {
                        // Call renderListData once with the entire array
                        renderListData(response.data);
                    } else {
                        $('#stats-display').html("<p>No battle types data available</p>");
                    }
                },
                error: function (error) {
                    $('#stats-display').html("<p>Error loading battle types: " + error.statusText + "</p>");
                    console.log("Error:", error);
                }
            });
        });

        // Enchantment Types Click Function
        $('#enchantmentTypes').on('click', function () {
            // Clear the display first
            $('#stats-display').empty();

            $.ajax({
                url: 'stat-fetch.php',
                method: 'GET',
                data: {
                    action: 'catalog',
                    type: 'enchantment-types'
                },
                dataType: 'json',
                success: function (response) {
                    if (response.data && response.data.length > 0) {
                        // Call renderListData once with the entire array
                        renderListData(response.data);
                    } else {
                        $('#stats-display').html("<p>No enchantment types data available</p>");
                    }
                },
                error: function (error) {
                    $('#stats-display').html("<p>Error loading enchantment types: " + error.statusText + "</p>");
                    console.log("Error:", error);
                }
            });
        });

        // Land Types Click Function
        $('#landTypes').on('click', function () {
            // Clear the display first
            $('#stats-display').empty();

            $.ajax({
                url: 'stat-fetch.php',
                method: 'GET',
                data: {
                    action: 'catalog',
                    type: 'land-types'
                },
                dataType: 'json',
                success: function (response) {
                    if (response.data && response.data.length > 0) {
                        // Call renderListData once with the entire array
                        renderListData(response.data);
                    } else {
                        $('#stats-display').html("<p>No land types data available</p>");
                    }
                },
                error: function (error) {
                    $('#stats-display').html("<p>Error loading land types: " + error.statusText + "</p>");
                    console.log("Error:", error);
                }
            });
        });

        // Planeswalker Types Click Function
        $('#planeswalkerTypes').on('click', function () {
            // Clear the display first
            $('#stats-display').empty();

            $.ajax({
                url: 'stat-fetch.php',
                method: 'GET',
                data: {
                    action: 'catalog',
                    type: 'planeswalker-types'
                },
                dataType: 'json',
                success: function (response) {
                    if (response.data && response.data.length > 0) {
                        // Call renderListData once with the entire array
                        renderListData(response.data);
                    } else {
                        $('#stats-display').html("<p>No planeswalker types data available</p>");
                    }
                },
                error: function (error) {
                    $('#stats-display').html("<p>Error loading planeswalker types: " + error.statusText + "</p>");
                    console.log("Error:", error);
                }
            });
        });

    }

    //Generates a list of sets in order of release date
    function renderSetsTimeline(sets) {
        const timeline = document.getElementById('stats-display');

        // Sort sets by release date
        const sortedSets = [...sets].sort((a, b) =>
            new Date(a.released_at) - new Date(b.released_at)
        );

        // Group by year
        const setsByYear = {};
        sortedSets.forEach(set => {
            const year = new Date(set.released_at).getFullYear();
            if (!setsByYear[year]) setsByYear[year] = [];
            setsByYear[year].push(set);
        });

        // Create timeline HTML
        let timelineHtml = '<div class="timeline">';
        Object.keys(setsByYear).sort().forEach(year => {
            timelineHtml += `
        <div class="timeline-year" data-year="${year}">
            <h4>${year}</h4>
            <div class="stat-card">
                <p>${setsByYear[year].length} sets</p>
            </div>
        </div>
        `;
        });
        timelineHtml += '</div>';

        // Add sets container for displaying sets when a year is clicked
        timelineHtml += '<div id="year-sets-display" class="sets-by-year"></div>';

        $(timeline).html(timelineHtml);

        // Add click handlers for each year of sets
        $('.timeline-year').on('click', function () {
            const year = $(this).data('year');
            const yearSets = setsByYear[year];

            // Highlight the selected year
            $('.timeline-year').removeClass('selected');
            $(this).addClass('selected');

            // Display the sets for the selected year
            let setsHtml = `<h3 id="set-head">Sets Released in ${year}</h3>`;
            setsHtml += '<div class="stats-grid">';

            // Sort sets within the year by release date
            yearSets.sort((a, b) => a.name.localeCompare(b.name));

            yearSets.forEach(set => {
                setsHtml += `
            <div class="stat-card noptr">
                <h4>${set.name}</h4>
                <p class="set-code">${set.code.toUpperCase()}</p>
                <p class="release-date">Released: ${new Date(set.released_at).toLocaleDateString()}</p>
                <p class="card-count">${set.card_count || 'Unknown'} cards</p>
            </div>
            `;
            });

            setsHtml += '</div>';
            $('#year-sets-display').html(setsHtml);
            $('#year-sets-display')[0].scrollIntoView();
        });

    }

    // Renders a list of items in a json list
    function renderListData(data) {
        const display = document.getElementById('stats-display');

        let displayHTML = '<div class="data-grid">';
        data.forEach(data => {
            displayHTML += `<div class="data-item">${data}</div>`;
        });

        displayHTML += '</div>';
        display.innerHTML = displayHTML;
    }

    fetchStatistics();

});