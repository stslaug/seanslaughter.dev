<?php
/*
 * This handles the statistics Page alongside stats.js
 * Specifically Sets/Catalogs from Scryfall
 */
function fetchCatalogData($endpoint)
{
    $url = "https://api.scryfall.com/" . $endpoint;
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

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $type = $_GET['type'];
    if ($action === 'catalog') {
        $cardData = fetchCatalogData('catalog/' . $type);
        echo json_encode($cardData);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action or missing required parameters']);
    }
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Fetch from Client to Server Not Properly Setup.']);
}