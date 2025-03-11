<?php
// Read JSON data from the text file
$file = "data.txt";



if (file_exists($file)) {
  $json_data = file_get_contents($file);

  // Decode JSON data into an associative array
  $data_array = json_decode($json_data, true);

  if ($data_array) {
    // Extract data from the array
    $season = isset($data_array['season']) ? $data_array['season'] : '';
    $livEnv = isset($data_array['livEnv']) ? $data_array['livEnv'] : '';
    $activity = isset($data_array['activity']) ? $data_array['activity'] : '';
    $relax = isset($data_array['relax']) ? $data_array['relax'] : '';
    $food = isset($data_array['food']) ? $data_array['food'] : '';
    $song = isset($data_array['song']) ? $data_array['song'] : '';
    $artist = isset($data_array['artist']) ? $data_array['artist'] : '';
    $fun = isset($data_array['fun']) ? $data_array['fun'] : '';
    $color = isset($data_array['color']) ? $data_array['color'] : '';
    $other = isset($data_array['other']) ? $data_array['other'] : '';

    // Create an associative array to hold the extracted data
    $data = array(
      'season' => $season,
      'livEnv' => $livEnv,
      'activity' => $activity,
      'relax' => $relax,
      'food' => $food,
      'song' => $song,
      'artist' => $artist,
      'fun' => $fun,
      'color' => $color,
      'other' => $other
    );

    // Send the data back to the client as JSON
    header('Content-Type: application/json');
    echo json_encode($data);
  } else {
    // Invalid JSON data
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(array('error' => 'Invalid JSON data'));
  }
} else {
  // File doesn't exist
  header('HTTP/1.1 404 Not Found');
  echo json_encode(array('error' => 'No data found'));
}
?>