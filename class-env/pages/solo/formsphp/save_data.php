<?php

/*
This assignment is designed to help you improve your ability to work with forms to collect data from user and write and
retrieve this data from a JSON file.

This will prepare you for your final semester project which integrates RAG technology with generative AI.

MODIFY this program to do the following
1. display a list of 10 personal questions
2. take the user's answers and write them to a JSON file
3. add a CLEAR button that will clear the form by removing the user's answers



*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the posted data which came from client
  $season = isset($_POST['season']) ? $_POST['season'] : '';
  $livEnv = isset($_POST['livEnv']) ? $_POST['livEnv'] : '';
  $activity = isset($_POST['activity']) ? $_POST['activity'] : '';
  $relax = isset($_POST['relax']) ? $_POST['relax'] : '';
  $food = isset($_POST['food']) ? $_POST['food'] : '';
  $song = isset($_POST['song']) ? $_POST['song'] : '';
  $artist = isset($_POST['artist']) ? $_POST['artist'] : '';
  $fun = isset($_POST['fun']) ? $_POST['fun'] : '';
  $color = isset($_POST['color']) ? $_POST['color'] : '';
  $other = isset($_POST['other']) ? $_POST['other'] : '';

  // prepare data to write to a text file using JSON
  $file = "data.txt";
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

  // Encode the data array to JSON
  $json_data = json_encode($data);

  // Write the JSON data to the file (overwriting any previous data)
  file_put_contents($file, $json_data);

  // Redirect back to the index page
  header("Location: formsphp.html");
  exit();
} else {
  echo "Invalid request!";
}
?>
