<?php

/*
 * I intend to use this file to store all of my database queries
 */
function getUserByUsername($username)
{

    require __DIR__ . './db_connect.php';

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
    // Usage: $user = getUserByUsername($username);
}

function getUserById($id)
{
    require __DIR__ . './db_connect.php';
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
    // Usage: $user = getUserById($id);
}


function getQuestionsForGame($gameId)
{
    require __DIR__ . '/db_connect.php';

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
