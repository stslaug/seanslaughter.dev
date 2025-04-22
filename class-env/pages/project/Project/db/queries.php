<?php

/*
 * I intend to use this file to store all of my database queries
 *
 *
 *  Honestly not sure if we need most of these.... (simply due to some session variables)
 */
function getUserByUsername($username)
{

    require_once __DIR__ . '/db_connect.php';

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
    // Usage: $user = getUserByUsername($username);
}

function getUserById($id)
{
    require_once __DIR__ . '/db_connect.php';
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
    // Usage: $user = getUserById($id);
}


function getQuestionsForGame($gameId)
{
    require_once __DIR__ . '/db_connect.php';

    // Join games → question_sets → questions
    $stmt = $conn->prepare("
        SELECT q.*
        FROM games g
        JOIN question_sets qs ON g.question_set_id = qs.id
        JOIN questions q ON q.question_set_id = qs.id
        WHERE g.id = ?
        ORDER BY q.id ASC
    ");

    $stmt->bind_param("i", $gameId);
    $stmt->execute();

    return $stmt->get_result();

    /*
     * This gets the questions for the associated game.

        $questions = getQuestionsForGame($gameId);

        $index = 0;
        while ($q = $questions->fetch_assoc()) {
            if ($index === $current_question_index) {
                echo "<p>Current Question: " . htmlspecialchars($q['question_text']) . "</p>";
            }
            $index++;
        }

 */

}

function getGamePlayers($gameId)
{
    require_once __DIR__ . '/db_connect.php';
    global $conn;
    $stmt = $conn->prepare("SELECT nickname, user_id FROM game_players WHERE game_id = ?");
    $stmt->bind_param("s", $gameId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
