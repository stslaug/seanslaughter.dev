$(document).ready(function () {
    let audio = document.getElementById("audio");
    let playPauseBtn = document.getElementById("playPauseBtn");
    let volumeControl = document.getElementById("volumeControl");
    let durationSpan = document.getElementById("duration");
    let currentTimeSpan = document.getElementById("currentTime");
    let progressBar = document.getElementById("progressBar");

    let updateInterval;
    let isDragging = false; // Add this line

    playPauseBtn.addEventListener("click", function () {
        if (audio.paused) {
            audio.play();
            playPauseBtn.classList.remove("fa-play");
            playPauseBtn.classList.add("fa-pause");
            updateInterval = setInterval(updateProgress, 10);
        } else {
            audio.pause();
            playPauseBtn.classList.remove("fa-pause");
            playPauseBtn.classList.add("fa-play");
            clearInterval(updateInterval);
        }
    });

    volumeControl.addEventListener("input", function () {
        audio.volume = volumeControl.value;
    });

    audio.addEventListener("loadedmetadata", function () {
        durationSpan.textContent = audio.duration.toFixed(2);
        progressBar.max = audio.duration; // Set the max of the progress slider
    });

    function updateProgress() {
        if (!audio.paused && !isDragging) { // add isDragging check.
            currentTimeSpan.textContent = audio.currentTime.toFixed(2);
            progressBar.value = audio.currentTime;
        }
    }

    progressBar.addEventListener("input", function () {
        isDragging = true; // add this line
        audio.pause();
        currentTimeSpan.textContent = progressBar.value.toFixed(2);
        audio.currentTime = progressBar.value;
    });

    progressBar.addEventListener("change", function () {
        isDragging = false; // Add this line
        audio.play();
        playPauseBtn.classList.remove("fa-play");
        playPauseBtn.classList.add("fa-pause");
        updateInterval = setInterval(updateProgress, 10);
    });

    function setTime(num) {
        audio.currentTime = num;
    }
});