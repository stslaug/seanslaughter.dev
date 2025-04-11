<?php
/**
 * File: leave_game.php
 * Description: Removes a player from the room JSON and redirects them to the homepage.
 *
 */

$room = $_POST['room'] ?? '';
$name = $_POST['name'] ?? '';
$path = "../rooms/$room.json";

if (file_exists($path)) {
    $data = json_decode(file_get_contents($path), true);

    // Remove player from the player list
    if (isset($data['players'])) {
        $data['players'] = array_values(array_filter($data['players'], function ($p) use ($name) {
            return $p !== $name;
        }));
    }

    // Remove vote if already submitted
    if (isset($data['voted'])) {
        $data['voted'] = array_values(array_filter($data['voted'], function ($p) use ($name) {
            return $p !== $name;
        }));
    }

    // Remove any votes given to this player
    if (isset($data['votes'][$name])) {
        unset($data['votes'][$name]);
    }

    // If the host left, reassign host to first available player
    if ($data['host'] === $name && !empty($data['players'])) {
        $data['host'] = $data['players'][0];
    }

    file_put_contents($path, json_encode($data));
}

header("Location: ../index.php");
exit;
