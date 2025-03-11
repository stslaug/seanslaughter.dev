<?php

class ZipcodeDistanceCalculator {
    private $zipcodeData = [];
    private $csvFile;

    /*
        Constructor causes classess woo
        Sets the file's location, and then prompts loading the user's data
    */
    public function __construct($csvFilePath) {
        // Determine the correct file path based on environment
        if ($_SERVER['SERVER_NAME'] == 'localhost') {

            $this->csvFile = 'uszips.csv';
        } else {
            // Server path
            $this->csvFile = '/home/seanljvy/TextFile/uszips.csv';
        }

        // If a specific path was provided, use that instead
        if (!empty($csvFilePath)) {
            $this->csvFile = $csvFilePath;
        }

        $this->loadZipcodeData();
    }

    /*
        This is what actually opens the text (csv) file and
         then grabs the data we care about
    */
    private function loadZipcodeData() {
        if (!file_exists($this->csvFile)) {
            throw new Exception("CSV file not found: {$this->csvFile}");
        }

        $file = fopen($this->csvFile, 'r');
        fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {
            // Strip quotes from values
            $zipcode = trim($row[0], '"');
            $lat = trim($row[1], '"');
            $lng = trim($row[2], '"');
            $city = trim($row[3], '"');
            $state = trim($row[5], '"');

            $this->zipcodeData[$zipcode] = [
                'lat' => $lat,
                'lng' => $lng,
                'city' => $city,
                'state' => $state
            ];
        }
        fclose($file);
    }

    // Grabs Zipcode Coords so we don't have to reference
   // "this->zipcodeData[$zipcode]" everytime
    public function getZipcodeCoordinates($zipcode) {
        if (isset($this->zipcodeData[$zipcode])) { // if it exists
            return $this->zipcodeData[$zipcode]; //get zip code for easier recalling
        }
        return false;
    }

    /**
     * used geeks for geeks for reference
     */
    private function calculateHaversineDistance($startLatitude, $startLongitude, $endLatitude, $endLongitude) {
        // Store calculation steps for display
        $calculationSteps = [];

        // Step 1: Initial coordinates
        $calculationSteps[] = [
            'step' => 'Initial Coordinates',
            'details' => [
                'startLatitude' => $startLatitude,
                'startLongitude' => $startLongitude,
                'endLatitude' => $endLatitude,
                'endLongitude' => $endLongitude
            ]
        ];

        // Step 2: Calculate differences in radians
        $latitudeDifference = ($endLatitude - $startLatitude) * M_PI / 180.0;
        $longitudeDifference = ($endLongitude - $startLongitude) * M_PI / 180.0;

        $calculationSteps[] = [
            'step' => 'Coordinate Differences (in radians)',
            'details' => [
                'latitudeDifference' => $latitudeDifference,
                'longitudeDifference' => $longitudeDifference,
                'formula' => "Δlat = (endLat - startLat) × π/180, Δlong = (endLong - startLong) × π/180"
            ]
        ];

        // Step 3: Convert coordinates to radians
        $startLatitudeRadians = ($startLatitude) * M_PI / 180.0;
        $endLatitudeRadians = ($endLatitude) * M_PI / 180.0;

        $calculationSteps[] = [
            'step' => 'Coordinates in Radians',
            'details' => [
                'startLatitudeRadians' => $startLatitudeRadians,
                'endLatitudeRadians' => $endLatitudeRadians,
                'formula' => "latRadians = lat × π/180"
            ]
        ];

        // Step 4: Calculate Haversine formula components
        $sinLatComponent = pow(sin($latitudeDifference / 2), 2);
        $sinLongComponent = pow(sin($longitudeDifference / 2), 2);
        $cosComponent = cos($startLatitudeRadians) * cos($endLatitudeRadians);

        $calculationSteps[] = [
            'step' => 'Haversine Formula Components',
            'details' => [
                'sinLatComponent' => $sinLatComponent,
                'sinLongComponent' => $sinLongComponent,
                'cosComponent' => $cosComponent,
                'formula' => "sin²(Δlat/2), sin²(Δlong/2), cos(startLatRad) × cos(endLatRad)"
            ]
        ];

        // Step 5: Calculate the haversine parameter (a)
        $haversineParameter = $sinLatComponent + $sinLongComponent * $cosComponent;

        $calculationSteps[] = [
            'step' => 'Haversine Parameter (a)',
            'details' => [
                'haversineParameter' => $haversineParameter,
                'formula' => "a = sin²(Δlat/2) + sin²(Δlong/2) × cos(startLatRad) × cos(endLatRad)"
            ]
        ];

        // Step 6: Calculate the angular distance (c)
        $angularDistance = 2 * asin(sqrt($haversineParameter));

        $calculationSteps[] = [
            'step' => 'Angular Distance (c)',
            'details' => [
                'angularDistance' => $angularDistance,
                'formula' => "c = 2 × asin(√a)"
            ]
        ];

        // Step 7: Calculate the distance
        $earthRadiusMiles = 3958.8; // Miles not kilo
        $distance = $earthRadiusMiles * $angularDistance;

        $calculationSteps[] = [
            'step' => 'Final Distance',
            'details' => [
                'earthRadiusMiles' => $earthRadiusMiles,
                'distance' => $distance,
                'formula' => "distance = R × c (where R is Earth's radius in miles)"
            ]
        ];

        // Final distance (that is also rounded for format sake)
        $roundedDistance = round($distance, 2);

        $calculationSteps[] = [
            'step' => 'Result',
            'details' => [
                'distance' => $roundedDistance . ' miles'
            ]
        ];

        return [
            'distance' => $roundedDistance,
            'steps' => $calculationSteps
        ];
    }

    public function getDistance($zipcode1, $zipcode2) {
        $coords1 = $this->getZipcodeCoordinates($zipcode1);
        $coords2 = $this->getZipcodeCoordinates($zipcode2);
        if (!$coords1) {
            return [
                'error' => true,
                'message' => "Zipcode '{$zipcode1}' not found in database"
            ];
        }
        if (!$coords2) {
            return [
                'error' => true,
                'message' => "Zipcode '{$zipcode2}' not found in database"
            ];
        }

        $calculationResult = $this->calculateHaversineDistance(
            $coords1['lat'], $coords1['lng'],
            $coords2['lat'], $coords2['lng']
        );

        // Now $calculationResult contains both the distance and all calculation steps
        return [
            'error' => false,
            'distance' => $calculationResult['distance'],
            'calculationSteps' => $calculationResult['steps'],
            'from' => [
                'zipcode' => $zipcode1,
                'city' => $coords1['city'],
                'state' => $coords1['state'],
                'lat' => $coords1['lat'],
                'lng' => $coords1['lng']
            ],
            'to' => [
                'zipcode' => $zipcode2,
                'city' => $coords2['city'],
                'state' => $coords2['state'],
                'lat' => $coords2['lat'],
                'lng' => $coords2['lng']
            ]
        ];
    }
}

// API endpoint to handle distance calculation requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // Get POST data
    $zipcode1 = isset($_POST['zipcode1']) ? trim($_POST['zipcode1']) : '';
    $zipcode2 = isset($_POST['zipcode2']) ? trim($_POST['zipcode2']) : '';

    // Validate zipcodes
    if (!preg_match('/^\d{5}$/', $zipcode1) || !preg_match('/^\d{5}$/', $zipcode2)) {
        echo json_encode([
            'error' => true,
            'message' => 'Error: Please provide valid US zipcodes (5 digits)'
        ]);
        exit;
    }

    try {
        $calculator = new ZipcodeDistanceCalculator('');  // Empty string will use the default path logic
        $result = $calculator->getDistance($zipcode1, $zipcode2);
        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode([
            'error' => true,
            'message' => "Error: " + $e->getMessage()
        ]);
    }
}
?>