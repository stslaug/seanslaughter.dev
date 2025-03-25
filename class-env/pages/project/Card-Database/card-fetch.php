<?php
/*
 * This card-fetch.php file is responsible for fetching data from the Scryfall API.
 *  This specifically is responsible for fetching cards.
 */
ini_set("allow_url_fopen", 1);
header('Content-Type: application/json');

/*
 * Fetches data from Scryfall API. This is a
 */
function fetchScryfallData($endpoint, $params = [])
{
    $baseUrl = "https://api.scryfall.com/";
    $url = $baseUrl . $endpoint;
    if (!empty($params)) {
        $url .= "?" . http_build_query($params);
    }

    // Create a stream
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: SeanSlaughterPortfolio/1.0",
            "Accept: application/json;q=0.9,*/*;q=0.8"
        ]
    ];
    $context = stream_context_create($opts);
    $response = file_get_contents($url, false, $context);
    $response = json_decode($response, true);


    if (isset($response["status"]) && $response["status"] != "200") {
        return ['error' => $response["error"] . "  R:" . $response["error"]];
    }

    return $response;
}

/*
 * Builds Scryfall search query string from parameters.
 */
function buildSearchQuery($params): string
{
    $query = [];
    if (!empty($params['cardName'])) {

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
        $colorQuery = '';
        if (isset($params['colorsMask'])) {
            $colorQuery = 'color' . $params['colorsMask'] . implode('', $colors);
        } else {
            $colorQuery = 'color=' . implode('', $colors);
        }
        $query[] = $colorQuery;
    }

// Add commander identity for commander formats
    $commanderFormats = ['commander', 'oathbreaker', 'brawl', 'pauper_commander', 'brawl_historic'];
    if (!empty($params['commanderColors'])) {
        $commanderColors = explode(',', $params['commanderColors']);
        $identityQuery = '';
        $identityQuery = 'id:' . implode('', $commanderColors);
        $query[] = $identityQuery;
    }

    if (isset($params['setName'])) {
        $query[] = 'set:' . $params['setName'];
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
        echo $cardData;
    } // Get a single card by ID
    else if ($action === 'getCardById' && isset($_GET['cardId'])) {
        $cardId = $_GET['cardId'];
        $cardData = fetchScryfallData("/cards/" . $cardId);
        echo json_encode($cardData);
    } // Search for cards with various parameters
    else if ($action === 'searchCards') {
        // Build search query from parameters
        $searchQuery = buildSearchQuery($_GET);

        if (empty($searchQuery)) { // This shouldn't happen but just in case.
            $cardData = fetchScryfallData("/cards/named", ["exact" => "Lightning Bolt"]);
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

            if (isset($searchData['error'])) {
                echo json_encode(['error' => $searchData['error']]);
            } else {
                echo json_encode([
                    'data' => $searchData['data'] ?? [],
                    'has_more' => $searchData['has_more'] ?? false,
                    'total_cards' => $searchData['total_cards'] ?? 0,
                ]);
            }
        }
    } // Unknown action
    else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action or missing required parameters']);
    }
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Fetch from Client to Server Not Properly Setup.']);
}