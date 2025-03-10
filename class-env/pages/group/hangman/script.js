jQuery(function () {
    let currWord = "";
    let attemptsLeft = 10;
    let guessedLetters = [];
    let cheatMode = $('#cheatMode');
    let cheatWord = $('#cheatWord');
    // Added Canvas
    let canvas = document.getElementById("gallows");
    let ctx = canvas.getContext("2d");

    $('#startBtn').on('click', startGame);
    cheatMode.on('change', updateCheatMode);

    function updateCheatMode() {
        cheatWord.toggle(300);
    }

    function startGame() {
        fetch('getWord.php')
            .then(response => response.json())
            .then(data => {
                if (data.word) {
                    currWord = data.word.toUpperCase();
                    setupGame(currWord);
                } else {
                    console.error('Error fetching word:', data.error);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function setupGame(word) {
        $('#wordToGuess').text('_ '.repeat(word.length).trim());
        cheatWord.text(word).hide();
        attemptsLeft = 10;
        $('#attempts').text(`Attempts Left: ${attemptsLeft}/10`);
        guessedLetters = [];
        resetCanvas(); // Clears the canvas at start of new game
        generateLetterButtons();
    }

    function generateLetterButtons() {
        const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $('#letters').empty();
        letters.split('').forEach(letter => {
            let button = $('<button>').text(letter).addClass('btn').click(() => guessLetter(letter));
            $('#letters').append(button);
        });
    }

    function guessLetter(letter) {
        if (guessedLetters.includes(letter)) return;
        guessedLetters.push(letter);

        if (currWord.includes(letter)) {
            updateWordDisplay();
            if (hasWon()) endGame(true);
        } else {
            attemptsLeft -= 1;
            $('#attempts').text(`Attempts Left: ${attemptsLeft}/10`);
            drawGallows(10 - attemptsLeft);
            if (attemptsLeft <= 0) endGame(false);
        }
    }

    function updateWordDisplay() {
        let displayWord = currWord.split('').map(l => (guessedLetters.includes(l) ? l : '_')).join(' ');
        $('#wordToGuess').text(displayWord);
    }

    function hasWon() {
        return $('#wordToGuess').text().replace(/\s/g, '') === currWord;
    }

    function endGame(won) {
        alert(won ? "You won!" : `You lost! The word was: ${currWord}`);
    }

    // Clears the canvas
    function resetCanvas() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.lineWidth = 4;
        ctx.strokeStyle = "#000000";
        ctx.beginPath();
        ctx.moveTo(50, 250);
        ctx.lineTo(150, 250); // Base
        ctx.moveTo(100, 250);
        ctx.lineTo(100, 50); // Pole
        ctx.moveTo(100, 50);
        ctx.lineTo(200, 50); // Top Beam
        ctx.moveTo(200, 50);
        ctx.lineTo(200, 80); // Rope
        ctx.stroke();
    }

    // Draws the Hangman
    function drawGallows(stage) {
        ctx.strokeStyle = "#000000";
        ctx.lineWidth = 4;
        ctx.beginPath();

        switch (stage) {
            case 1:
                ctx.arc(200, 100, 20, 0, Math.PI * 2);
                break; // Head
            case 2:
                ctx.moveTo(200, 120);
                ctx.lineTo(200, 180);
                break; // Body
            case 3:
                ctx.moveTo(200, 130);
                ctx.lineTo(170, 160);
                break; // Left Arm
            case 4:
                ctx.moveTo(200, 130);
                ctx.lineTo(230, 160);
                break; // Right Arm
            case 5:
                ctx.moveTo(200, 180);
                ctx.lineTo(170, 230);
                break; // Left Leg
            case 6:
                ctx.moveTo(200, 180);
                ctx.lineTo(230, 230);
                break; // Right Leg
            case 7:
                ctx.moveTo(170, 230);
                ctx.lineTo(160, 250);
                break; // Left Foot
            case 8:
                ctx.moveTo(230, 230);
                ctx.lineTo(240, 250);
                break; // Right Foot
            case 9:
                ctx.moveTo(170, 160);
                ctx.lineTo(160, 140);
                break; // Left Hand
            case 10:
                ctx.moveTo(230, 160);
                ctx.lineTo(240, 140);
                break; // Right Hand
        }

        ctx.stroke();
    }

    startGame();
});