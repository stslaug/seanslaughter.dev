<?php
header('Content-Type: application/json');

/**
 * Fetches data from Scryfall API.
 */
function fetchScryfallData($endpoint, $params = [])
{
    $baseUrl = "https://api.scryfall.com";
    $url = $baseUrl . $endpoint;
    if (!empty($params)) {
        $url .= "?" . http_build_query($params);
    }

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "User-Agent: SeanSlaughterPortfolio/1.0",
            "Accept: application/json;q=0.9,*/*;q=0.8",
        ],
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ]);

    $response = curl_exec($curl);
    if ($response === false) {
        $error = curl_error($curl);
        curl_close($curl);
        return ['error' => $error];
    }

    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($httpCode !== 200) {
        return ['error' => "HTTP error: " . $httpCode, 'response' => $response];
    }

    // Check if response is valid JSON
    json_decode($response);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['error' => "JSON decoding error: " . json_last_error_msg(), 'response' => $response];
    }

    // Return the raw response when we're just going to encode it again later
    return $response;
}

/**
 * Builds Scryfall search query string from parameters.
 */
function buildSearchQuery($params)
{
    $query = [];

    // Add card name
    if (isset($params['cardName']) && !empty($params['cardName'])) {
        $query[] = 'name:' . urlencode('"' . $params['cardName'] . '"');
    }

    // Add type line
    if (isset($params['typeLine']) && !empty($params['typeLine'])) {
        $query[] = 't:' . urlencode('"' . $params['typeLine'] . '"');
    }

    // Add mana cost
    if (isset($params['convManaCost']) && is_numeric($params['conManaCost'])) {
        $query[] = 'cmc=' . $params['manaCost'];
    }

    // Add format legality
    if (isset($params['format']) && !empty($params['format'])) {
        $query[] = 'f:' . $params['format'];
    }

    // Add colors
    if (isset($params['colors']) && !empty($params['colors'])) {
        $colors = explode(',', $params['colors']);
        $colorQuery = isset($params['colorsExact']) && $params['colorsExact'] == 1
            ? 'c:' . implode('', $colors) : 'c<=' . implode('', $colors);
        $query[] = $colorQuery;
    }

    // Add commander identity for commander formats
    $commanderFormats = ['commander', 'oathbreaker', 'brawl', 'pauper_commander', 'brawl_historic'];
    if (isset($params['format']) && in_array($params['format'], $commanderFormats) &&
        isset($params['commanderColors']) && !empty($params['commanderColors'])) {

        $commanderColors = explode(',', $params['commanderColors']);
        $identityQuery = isset($params['commanderExact']) && $params['commanderExact'] == 1
            ? 'id:' . implode('', $commanderColors)
            : 'id<=' . implode('', $commanderColors);
        $query[] = $identityQuery;
    }

    return implode(' ', $query);
}

// Handle different API actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Get a single card by exact name
    if ($action === 'getCard' && isset($_GET['cardName'])) {
        $cardName = $_GET['cardName'];
        $cardData = fetchScryfallData("/cards/named", ["exact" => $cardName]);
        echo $cardData; // Already JSON, no need to re-encode
    } // Get a single card by ID
    else if ($action === 'getCardById' && isset($_GET['cardId'])) {
        $cardId = $_GET['cardId'];
        $cardData = fetchScryfallData("/cards/" . $cardId);
        echo $cardData; // Already JSON, no need to re-encode
    } // Search for cards with various parameters
    else if ($action === 'searchCards') {
        // Build search query from parameters
        $searchQuery = buildSearchQuery($_GET);

        if (empty($searchQuery)) {
            // If no search parameters provided, return a sample card for testing
            $cardData = fetchScryfallData("/cards/named", ["exact" => "Lightning Bolt"]);
            // Need to handle this special case differently since we're formatting the response
            $decodedCardData = json_decode($cardData, true);
            echo json_encode(['data' => [$decodedCardData]]);
        } else {
            // Execute search
            $searchData = fetchScryfallData("/cards/search", [
                'q' => $searchQuery,
                'order' => 'name',
                'unique' => 'cards',
                'page' => isset($_GET['page']) ? intval($_GET['page']) : 1,
            ]);

            // If searchData is an array with error, it came from our error handling
            if (is_array($searchData) && isset($searchData['error'])) {
                echo json_encode(['error' => $searchData['error']]);
            } else {
                // For the search results, we need to decode to access the data property
                $decodedData = json_decode($searchData, true);
                echo json_encode([
                    'data' => $decodedData['data'] ?? [],
                    'has_more' => $decodedData['has_more'] ?? false,
                    'total_cards' => $decodedData['total_cards']
                ]);
            }
        }
    } // Unknown action
    else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action or missing required parameters']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No action specified']);
}