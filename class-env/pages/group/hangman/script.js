jQuery(function () {
    let currWord = "";
    let attemptsLeft = 0;
    let guessedLetters = [];
    let cheatMode = $('#cheatMode');
    let cheatWord = $('#cheatWord');

    $('#startBtn').on('click', startGame);

    cheatMode.on('change', updateCheatMode);

    function updateCheatMode() {
        if (document.getElementById('cheatMode').checked) {
            $(cheatWord).fadeIn(300);

        } else {
            $(cheatWord).fadeOut(300);

        }
    }

    function startGame() {
        // Fetch a new word from the server
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
        const wordToGuess = document.getElementById('wordToGuess');
        const cheatWord = document.getElementById('cheatWord');

        wordToGuess.innerHTML = '_ '.repeat(word.length).trim();
        $(cheatWord).fadeOut(0);
        updateCheatMode();
        cheatWord.innerText = word;
        attemptsLeft = 10; // Reset Player Score
        document.getElementById("attempts").innerText = `Attempts Left: ${attemptsLeft}/10`;
        guessedLetters = []; // Reset Guessed Letters

        generateLetterButtons();
    }

    function generateLetterButtons() {
        const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        const lettersDiv = document.getElementById('letters');
        lettersDiv.innerHTML = ''; // Clear previous buttons
        letters.split('').forEach(letter => {
            const button = document.createElement('button');
            button.textContent = letter;
            button.id = letter;
            button.classList.add('btn');
            button.onclick = () => guessLetter(letter);
            lettersDiv.appendChild(button);
        });
    }

    let timeoutId;

    function guessLetter(letter) {
        // Prevent clicking the same letter twice
        if (guessedLetters.includes(letter)) return;

        guessedLetters.push(letter);

        if (timeoutId) {
            clearTimeout(timeoutId);
        }

        if (currWord.includes(letter)) {
            $('#' + letter).css('background-color', '#555555');
            $(document.getElementById('letters')).css('backgroundColor', 'var(--green)');


            updateWordDisplay();

            if (hasWon()) {
                endGame(true);
                return;
            }
        } else {
            attemptsLeft -= 1;
            document.getElementById("attempts").innerText = `Attempts Left: ${attemptsLeft}/10`;
            if (attemptsLeft <= 0) {
                endGame(false);
                return;
            }
            updateGallows();
            $('#' + letter).css('background-color', '#555555');
            $(document.getElementById('letters')).css('backgroundColor', ' rgba(205, 92, 92, 1)');
        }

        timeoutId = setTimeout(function () {
            $(document.getElementById('letters')).css('backgroundColor', '');
            timeoutId = null;
        }, 1000);
    }

    function updateWordDisplay() {
        const wordToGuess = document.getElementById('wordToGuess');
        let displayWord = '';

        for (let i = 0; i < currWord.length; i++) {
            if (guessedLetters.includes(currWord[i])) {
                displayWord += currWord[i] + ' ';
            } else {
                displayWord += '_ ';
            }
        }

        wordToGuess.innerText = displayWord.trim();
    }

    function updateGallows() {
        /*
            TODO Implement Gallows on Hangman Update
           Check attemptsleft then switch statement through changing/clipping through the img?
         */
    }

    function hasWon() {
        const wordToGuess = document.getElementById('wordToGuess');
        let currGuess = (wordToGuess.innerText).replaceAll(/\s/g, '');
        console.log("hasWonCalled and is it true? " + ((currGuess == currWord) ? 'yes.' : 'no.') + " currWord: \"" + currWord + "\" currGuess: \"" + currGuess + "\"");
        return (currGuess === currWord)
        {
            /*
                  TODO Maybe Some kind of win effect?
              */
        }

    }

    function endGame() {
        console.log("Game has Ended: Player has " + ((attemptsLeft > 0) ? 'won.' : 'lost.'))

    }

// Initially start the game
    startGame();
});