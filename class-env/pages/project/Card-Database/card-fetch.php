<?php
header('Content-Type: application/json');

/*
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
        return ['error' => $error . "  R:" . $response];
    }

    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);


    // Check if response is valid JSON
    json_decode($response);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['error' => "JSON decoding error: " . json_last_error_msg(), 'response' => $response];
    }
    if ($httpCode !== 200) {
        return ['error' => "HTTP error: " . $httpCode . "  resp: " . $response, 'response' => $response];
    }

    // Return the raw response when we're just going to encode it again later
    return $response;
}

/*
 * Builds Scryfall search query string from parameters.
 */
/*
 * Builds Scryfall search query string from parameters.
 */
function buildSearchQuery($params): string
{
    $query = [];

    // Add card name (no specific prefix for name search)
    if (!empty($params['cardName'])) {
        // For card names with spaces, add quotes
        if (strpos($params['cardName'], ' ') !== false) {
            $query[] = '"' . $params['cardName'] . '"';
        } else {
            $query[] = $params['cardName'];
        }
    }

    // Add type line
    if (!empty($params['typeLine'])) {
        foreach ($params['typeLine'] as $line) {
            $query[] = 't:' . $line;
        }

    }

    // Add mana cost
    if (isset($params['convManaCost']) && is_numeric($params['convManaCost'])) {
        $query[] = 'cmc=' . $params['convManaCost']; // Notice = not : for cmc
    }

    // Add format legality
    if (!empty($params['legal'])) {
        $query[] = 'legal:' . $params['legal'];
    }

    // Add colors
    if (!empty($params['colors'])) {
        $colors = explode(',', $params['colors']);
        $colorQuery = isset($params['colorsExact']) && $params['colorsExact'] == 1
            ? 'color:' . implode('', $colors) : 'c<=' . implode('', $colors);
        $query[] = $colorQuery;
    }

    // Add commander identity for commander formats
    $commanderFormats = ['commander', 'oathbreaker', 'brawl', 'pauper_commander', 'brawl_historic'];
    if (isset($params['format']) && in_array($params['format'], $commanderFormats) &&
        !empty($params['commanderColors'])) {
        $commanderColors = explode(',', $params['commanderColors']);
        $identityQuery = isset($params['commanderExact']) && $params['commanderExact'] == 1
            ? 'id:' . implode('', $commanderColors)
            : 'id<=' . implode('', $commanderColors);
        $query[] = $identityQuery;
    }

    // Join with spaces, not ampersands
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
                'include_extras' => false,
                'page' => isset($_GET['page']) ? intval($_GET['page']) : 1,
            ]);

            // If searchData is an array with error, it came from our error handling
            if (isset($searchData['error'])) {
                echo json_encode(['error' => $searchData['error']]);
            } else {
                // For the search results, we need to decode to access the data property
                $decodedData = json_decode($searchData, true);
                echo json_encode([
                    'data' => $decodedData['data'] ?? [],
                    'has_more' => $decodedData['has_more'] ?? false,
                    'total_cards' => $decodedData['total_cards'] ?? 0,
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