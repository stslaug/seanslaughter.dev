jQuery(function () {

    $(document).on('change', '.fav', function () {
        // Get whether the checkbox is now checked or unchecked
        const isChecked = $(this).is(':checked');

        // Get the card ID from the data attribute
        const cardId = $(this).data('card-id');


        // Do something with that info
        if (isChecked) {
            $.ajax({
                url: 'card-fetch.php', method: 'GET', data: {
                    action: 'getCardById', cardId: cardId
                }, dataType: 'json', success: function (card) {
                    if (card.error) {
                        alert('Error fetching card details: ' + card.error);
                    } else {
                        addCardToFavorites(card);
                    }
                }, error: function (xhr, status, error) {
                    alert('Error fetching card details: ' + error);
                }
            });
        } else {
            console.log(`Checkbox for card ${cardId} was unchecked!`);
            removeCardFromFavorites(cardId);

        }
    });


    function addCardToFavorites(card) {
        $.ajax({
            url: '/class-env/pages/project/Card-Database/pages/favorites/favorites-ajax.php',
            method: 'POST',
            data: {
                action: 'add',
                cardId: card.id,
                name: card.name,
                image_url: card.image_uris?.normal || '',
                type_line: card.type_line || '',
                color: card.color_identity.join(',') || ''
            },
            success: function () {
                console.log(`Card ${card.id} added to favorites`);
            },
            error: function (xhr) {
                console.error('Error adding card:', xhr.responseText);
            }
        });
    }

    function removeCardFromFavorites(cardId) {
        $.ajax({
            url: '/class-env/pages/project/Card-Database/pages/favorites/favorites-ajax.php',
            method: 'POST',
            data: {
                action: 'remove',
                cardId: cardId
            },
            success: function () {
                console.log(`Card ${cardId} removed from favorites`);
            },
            error: function (xhr) {
                console.error('Error removing card:', xhr.responseText);
            }
        });
    }

});