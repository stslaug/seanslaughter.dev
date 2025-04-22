CREATE DATABASE IF NOT EXISTS project3;
USE project3;

-- USERS table
CREATE TABLE users
(
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username          VARCHAR(50)  NOT NULL UNIQUE,
    password          VARCHAR(255) NOT NULL,
    security_question TEXT         NOT NULL,
    security_answer   VARCHAR(255) NOT NULL,
    last_login        DATETIME,
    games_played      INT       DEFAULT 0,
    votes_cast        INT       DEFAULT 0,
    votes_received    INT       DEFAULT 0,
    created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- QUESTION SETS
CREATE TABLE question_sets
(
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    INT UNSIGNED,
    set_name   VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

-- QUESTIONS
CREATE TABLE questions
(
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question_text   TEXT         NOT NULL,
    question_set_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (question_set_id) REFERENCES question_sets (id) ON DELETE CASCADE
);

-- GAMES
CREATE TABLE games
(
    id                     VARCHAR(6) PRIMARY KEY,
    host_user_id           INT UNSIGNED,
    question_set_id        INT UNSIGNED NOT NULL,
    current_question_index INT       DEFAULT 0,
    created_at             TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active              BOOLEAN   DEFAULT TRUE,
    FOREIGN KEY (host_user_id) REFERENCES users (id) ON DELETE SET NULL,
    FOREIGN KEY (question_set_id) REFERENCES question_sets (id) ON DELETE CASCADE
);

-- GAME PLAYERS
CREATE TABLE game_players
(
    id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    game_id   VARCHAR(6)  NOT NULL,
    user_id   INT UNSIGNED,
    nickname  VARCHAR(50) NOT NULL,
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (game_id) REFERENCES games (id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL
);

CREATE TABLE votes
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    game_id        VARCHAR(6),
    question_index INT,
    voter_id       INT,
    vote_for_id    INT,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (game_id) REFERENCES games (id) ON DELETE CASCADE,
    FOREIGN KEY (voter_id) REFERENCES game_players (id) ON DELETE CASCADE,
    FOREIGN KEY (vote_for_id) REFERENCES game_players (id) ON DELETE CASCADE
);