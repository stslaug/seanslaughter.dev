<?php
header("Content-type: text/xml");

$states_and_capitals = array(
    "Alabama, Montgomery", "Alaska, Juneau", "Arizona, Phoenix",
    "Arkansas, Little Rock", "California, Sacramento", "Colorado, Denver",
    "Connecticut, Hartford", "Delaware, Dover", "Florida, Tallahassee",
    "Georgia, Atlanta", "Hawaii, Honolulu", "Idaho, Boise",
    "Illinois, Springfield", "Indiana, Indianapolis", "Iowa, Des Moines",
    "Kansas, Topeka", "Kentucky, Frankfort", "Louisiana, Baton Rouge",
    "Maine, Augusta", "Maryland, Annapolis", "Massachusetts, Boston",
    "Michigan, Lansing", "Minnesota, Saint Paul", "Mississippi, Jackson",
    "Missouri, Jefferson City", "Montana, Helena", "Nebraska, Lincoln",
    "Nevada, Carson City", "New Hampshire, Concord", "New Jersey, Trenton",
    "New Mexico, Santa Fe", "New York, Albany", "North Carolina, Raleigh",
    "North Dakota, Bismarck", "Ohio, Columbus", "Oklahoma, Oklahoma City",
    "Oregon, Salem", "Pennsylvania, Harrisburg", "Rhode Island, Providence",
    "South Carolina, Columbia", "South Dakota, Pierre", "Tennessee, Nashville",
    "Texas, Austin", "Utah, Salt Lake City", "Vermont, Montpelier",
    "Virginia, Richmond", "Washington, Olympia", "West Virginia, Charleston",
    "Wisconsin, Madison", "Wyoming, Cheyenne"
);

echo "<?xml version=\"1.0\" ?>\n";
echo "<states>\n";

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    foreach ($states_and_capitals as $state_capital) {
        $parts = explode(", ", $state_capital);
        $state = $parts[0];
        $capital = $parts[1];

        if (stripos($state, $query) !== false) { // Use stripos for case-insensitive search
            echo "<state>\n";
            echo "  <name>$state</name>\n";
            echo "  <capital>$capital</capital>\n";
            echo "</state>\n";
        }
    }
}

echo "</states>\n";
?>