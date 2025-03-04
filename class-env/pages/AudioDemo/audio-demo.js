document.addEventListener('DOMContentLoaded', function () {
    // DOM Element References
    const createTitleForm = document.getElementById("createTitle");
    const newTitleInput = document.getElementById("newTitle");
    const timestampInput = document.getElementById("timestamp");
    const tableBody = document.querySelector("#titles tbody");
    const createTitleBtn = document.getElementById("createTitleBtn");
    const audio = document.getElementById("audio");
    const playPauseBtn = document.getElementById("playPauseBtn");
    const progressBar = document.getElementById("progressBar");
    const duration = document.getElementById("duration");
    const currentTimeSpan = document.getElementById("currentTime");
    const volumeControl = document.getElementById("volumeControl");

    let wasPaused = true;
    let sortAscending = true;

    // Add sorting functionality to the time header
    const timeHeader = $(".time-column").first();
    timeHeader.append(' <i class="fa-solid fa-sort"></i>');
    timeHeader.css("cursor", "pointer");
    timeHeader.on("click", function () {
        sortAscending = !sortAscending;
        sortTitles();
        const icon = $(this).find("i");
        icon.removeClass("fa-sort fa-sort-up fa-sort-down");
        icon.addClass(sortAscending ? "fa-sort-up" : "fa-sort-down");
    });

    // Sorts the table rows based on time
    function sortTitles() {
        const rows = $("#titles tbody tr").get();
        rows.sort(function (a, b) {
            const timeA = getSeconds($(a).find(".time-column").text());
            const timeB = getSeconds($(b).find(".time-column").text());
            return sortAscending ? timeA - timeB : timeB - timeA;
        });
        $.each(rows, function (index, row) {
            $("#titles tbody").append(row);
        });
    }


    // Converts mm:ss time format to seconds
    function getSeconds(timeStr) {
        const parts = timeStr.split(':');
        return parseInt(parts[0]) * 60 + parseInt(parts[1]);
    }

    // Formats seconds to mm:ss format
    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${minutes}:${secs < 10 ? '0' : ''}${secs}`;
    }

    // Toggles play/pause functionality of the audio
    function togglePlayPause() {
        if (audio.paused) {
            audio.play();
            playPauseBtn.classList.replace("fa-play", "fa-pause");
            wasPaused = false;
        } else {
            audio.pause();
            playPauseBtn.classList.replace("fa-pause", "fa-play");
            wasPaused = true;
        }
    }

    // Updates the progress bar and current time display
    function updateProgressBar() {
        currentTimeSpan.textContent = formatTime(audio.currentTime);
        progressBar.value = audio.currentTime;
    }

    // Jumps to a specific time in the audio
    function jumpToTime(time) {
        audio.currentTime = time;
        updateProgressBar();
    }

    // Initialize audio player in paused state
    audio.pause();

    // Creates a new title entry row in the table
    function createTitleEntry(title, time) {
        const row = $("<tr></tr>");
        $("<td></td>").addClass("title-column").attr("data-label", "Title").text(title).appendTo(row);
        $("<td></td>").addClass("time-column").attr("data-label", "Time").text(formatTime(time)).appendTo(row);
        const controls = $("<td></td>").attr("data-label", "Controls").html('<div class="controls"><i class="fas fa-play fa-2xl"></i><i class="fa-solid fa-trash fa-2xl"></i></div>').appendTo(row);
        $("#titles tbody").append(row);

        row.find(".fa-play").on("click", function () {
            jumpToTime(time);
            if (audio.paused) {
                togglePlayPause();
            }
        });

        row.find(".fa-trash").on("click", function () {
            row.remove();
            if (audio.currentTime >= time && !audio.paused) {
                audio.pause();
                playPauseBtn.classList.replace("fa-pause", "fa-play");
                wasPaused = true;
            }
        });
    }

    function addPresetTitles(titles) {
        titles.forEach(title => {
            createTitleEntry(title.title, title.time);
        });
        sortTitles();
    }

    addPresetTitles([
        {title: "The beginning part 2", time: 15},
        {title: "Just getting started", time: 100},
        {title: "You've listened for a while", time: 200},
        {title: "Almost there", time: 203}
    ]);

    // Adds preset titles to the table when audio metadata is loaded
    audio.addEventListener("loadedmetadata", function () {
        duration.textContent = formatTime(audio.duration);
        progressBar.max = audio.duration;
        timestampInput.max = audio.duration;
        $('fileName').textContent = "File Name: " + audio.namespaceURI + ".";

    });

    // Updates progress bar and checks for title timestamps during playback
    audio.addEventListener("timeupdate", function () {
        updateProgressBar();
        const currentTime = audio.currentTime;
        const titleRows = $("#titles tbody tr");
        titleRows.each(function () {
            const timeInSeconds = getSeconds($(this).find(".time-column").text());

            if (Math.abs(currentTime - timeInSeconds) < 0.25 && !audio.paused) {
                audio.pause();
                playPauseBtn.classList.replace("fa-pause", "fa-play");
                wasPaused = true;
                audio.currentTime = timeInSeconds + 0.26;
                $(this).addClass("highlighted");
                setTimeout(() => {
                    $(this).removeClass("highlighted");
                }, 5000);
                return false;
            }
        });
    });

    // Handles play/pause button click
    playPauseBtn.addEventListener("click", togglePlayPause);

    // Handles volume control input
    volumeControl.addEventListener("input", function () {
        audio.volume = this.value;
    });

    // Handles progress bar input (pause while dragging)
    progressBar.addEventListener("input", function () {
        audio.pause();
        jumpToTime(parseFloat(this.value));
    });

    // Handles progress bar change (resume playback if it was playing)
    progressBar.addEventListener("change", function () {
        if (!wasPaused) {
            audio.play();
            playPauseBtn.classList.replace("fa-play", "fa-pause");
        }
    });

    // Updates the "Create Title" button text based on timestamp input
    timestampInput.addEventListener("input", function () {
        createTitleBtn.value = `Create Title At: ${formatTime(this.value)}`;
    });

    // Handles form submission to create a new title
    createTitleForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const title = newTitleInput.value.trim();
        const time = parseFloat(timestampInput.value);
        if (!title) {
            alert("Please enter a title.");
            return;
        }
        createTitleEntry(title, time);
        sortTitles();
        newTitleInput.value = "";
        timestampInput.value = 0;
        createTitleBtn.value = "Create Title At: 0:00";
    });
});