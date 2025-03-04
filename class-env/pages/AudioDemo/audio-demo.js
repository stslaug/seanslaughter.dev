document.addEventListener('DOMContentLoaded', function () {
    // DOM Element References
    const createTitleForm = document.getElementById("createTitle");
    const newTitleInput = document.getElementById("newTitle");
    const timestampInput = document.getElementById("timestamp");
    const tableBody = document.querySelector("#titles tbody");
    const createTitleBtn = document.getElementById("createTitleBtn");
    const timeSelect = document.getElementById("timeSelect");
    const audio = document.getElementById("audio");
    const playPauseBtn = document.getElementById("playPauseBtn");
    const progressBar = document.getElementById("progressBar");
    const duration = document.getElementById("duration");
    const currentTimeSpan = document.getElementById("currentTime");
    const volumeControl = document.getElementById("volumeControl");
    const titleCount = document.getElementById("titleCount");
    let wasPaused = true;
    let sortAscending = true;
    let totalTitles = 0;

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


    function updateTotalTitles(num = 1) {
        totalTitles += num;
        titleCount.innerHTML = totalTitles + " title(s)";
    }

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
        // Check if the string contains a colon
        if (timeStr.includes(':')) {
            const parts = timeStr.split(':');
            return parseInt(parts[0]) * 60 + parseInt(parts[1]);
        } else {
            // If no colon, assume the value is already in seconds
            return parseInt(timeStr);
        }
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
            duration.textContent = formatTime(audio.duration);
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

    // Jumps to a specific togglePlayPause in the audio
    function jumpToTime(time) {
        audio.currentTime = time;
        updateProgressBar();
    }

    // Creates a new title entry row in the table
    function createTitleEntry(title, time) {
        let row = $("<tr></tr>"); // Create the row
        $("<td></td>").addClass("title-column").attr("data-label", "Title").text(title).appendTo(row);
        $("<td></td>").addClass("time-column").attr("data-label", "Time").text(formatTime(time)).appendTo(row);
        let controls = $("<td></td>").attr("data-label", "Controls").html('<div class="controls">' +
            '<i class="fas fa-play fa-2xl"></i><i class="fa-solid fa-trash fa-2xl"></i></div>').appendTo(row);
        $("#titles tbody").append(row);
        updateTotalTitles(1);

        // Controller Event Handlers
        row.find(".fa-play").on("click", function () {
            jumpToTime(time);
            togglePlayPause();
        });

        row.find(".fa-trash").on("click", function () {
            row.remove();
            updateTotalTitles(-1);
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

    function updateFileName() {
        duration.textContent = formatTime(audio.duration);
        progressBar.max = audio.duration;
        timestampInput.max = audio.duration;
        $("#fileName").html("Audio File Name: \"" + audio.localName + "\"");


    }

    updateFileName();

    addPresetTitles([
        {title: "The beginning part 2", time: 15},
        {title: "Just getting started", time: 100},
        {title: "You've listened for a while", time: 200},
        {title: "Almost there", time: 203}
    ]);

    // Adds preset titles to the table when audio metadata is loaded


    // Updates progress bar and checks for title timestamps during playback
    audio.addEventListener("timeupdate", function () {
        updateProgressBar();
        const currentTime = audio.currentTime;
        const titleRows = $("#titles tbody tr");
        titleRows.each(function () {
            const timeInSeconds = getSeconds($(this).find(".time-column").text());
            let timeCalc = (currentTime - timeInSeconds);
            if (timeCalc > (-0.25) && timeCalc < 0 && !audio.paused) {
                audio.pause();
                playPauseBtn.classList.replace("fa-pause", "fa-play");
                wasPaused = true;
                audio.currentTime = timeInSeconds;
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
        timeSelect.value = `${formatTime(this.value)}`;
        createTitleBtn.value = `Create Title At: ${formatTime(this.value)}`;
    });

    // Updates the "Create Title" button text based on timestamp input
    timeSelect.addEventListener("input", function () {

        let inputTime = formatTime(getSeconds(timeSelect.value));

        timestampInput.value = timeSelect.value;
        createTitleBtn.value = `Create Title At: ${inputTime}`;
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
        timeSelect.value = "";
        newTitleInput.value = "";
        timestampInput.value = 0;
        createTitleBtn.value = "Create Title At: 0:00";
    });

    $('#popDataBtn').on('click', function () {
        console.log("push");
        addPresetTitles([
            {title: "Title 1", time: 5},
            {title: "Title 2", time: 12},
            {title: "Title 3", time: 20},
            {title: "Title 4", time: 28},
            {title: "Title 5", time: 35},
            {title: "Title 6", time: 42},
            {title: "Title 7", time: 49},
            {title: "Title 8", time: 56},
            {title: "Title 9", time: 63},
            {title: "Title 10", time: 70},
            {title: "Title 11", time: 77},
            {title: "Title 12", time: 84},
            {title: "Title 13", time: 91},
            {title: "Title 14", time: 98},
            {title: "Title 15", time: 105},
            {title: "Title 16", time: 112},
            {title: "Title 17", time: 119},
            {title: "Title 18", time: 126},
            {title: "Title 19", time: 133},
            {title: "Title 20", time: 140},
            {title: "Title 21", time: 147},
            {title: "Title 22", time: 154},
            {title: "Title 23", time: 161},
            {title: "Title 24", time: 168},
            {title: "Title 25", time: 175},
            {title: "Title 26", time: 182},
            {title: "Title 27", time: 189},
            {title: "Title 28", time: 196},
            {title: "Title 29", time: 203},
            {title: "Title 30", time: 210},
            {title: "Title 31", time: 217},
            {title: "Title 32", time: 224},
            {title: "Title 33", time: 231},
            {title: "Title 34", time: 238},
            {title: "Title 35", time: 245},
            {title: "Title 36", time: 252},
            {title: "Title 37", time: 259},
            {title: "Title 38", time: 266},
            {title: "Title 39", time: 273},
            {title: "Title 40", time: 280},
            {title: "Title 41", time: 287},
            {title: "Title 42", time: 294},
            {title: "Title 43", time: 301},
            {title: "Title 44", time: 308},
            {title: "Title 45", time: 315},
            {title: "Title 46", time: 322},
            {title: "Title 47", time: 329},
            {title: "Title 48", time: 336},
            {title: "Title 49", time: 343},
            {title: "Title 50", time: 350}

        ]);
    })

    $('#prevBtn').on('click', function () {
        jumpToTime(audio.currentTime - 5);
    });
    $('#fwdBtn').on('click', function () {
        jumpToTime(audio.currentTime + 5);
    });
});