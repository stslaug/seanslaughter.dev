document.addEventListener("DOMContentLoaded", function() {

    /*
     *
     *
     * Animation Events Section
     * 
     * 
     */
    let iteration = 0;
    let colorBox = document.getElementById("color-box");

    if (colorBox) {
        colorBox.addEventListener("animationiteration", function(event) {
            let time = document.getElementById("elapsed-time");
            if (time) {
                time.textContent = "Elapsed Time: " + event.elapsedTime; // Use textContent
            }

            let animationName = document.getElementById("animation-name");
            if (animationName) {
                animationName.textContent = "Animation Name: " + event.animationName; // Use textContent
            }

            let iterations = document.getElementById("iterations");
            if (iterations) {
                iterations.textContent = "Iterations: " + ++iteration; // Use textContent
            }
        });
    }


    /*
     *
     *
     * Drag Events Section
     * 
     * 
     */

    let dragme = document.getElementById("dragme");
    if(dragme)
    {
        dragme.addEventListener("dragstart", function(event) {
            event.dataTransfer.dropEffect = "move";
            event.dataTransfer.setData("Text", event.target.id);
            document.getElementById("dragStatus").textContent = "Dragging: True"; // Use textContent
            event.target.style.opacity = "1";

            let dragZones = document.getElementsByClassName("dragZone");

            for (let i = 0; i < dragZones.length; i++) {
                dragZones[i].style.border = "2px dotted green"; // Consistent style setting
            }
        });

        dragme.addEventListener("dragend", function(event) {
            event.preventDefault(); // Might not be needed here
            document.getElementById("dragStatus").textContent = "Dragging: False"; // Use textContent
            let dragZones = document.getElementsByClassName("dragZone");

            for (let i = 0; i < dragZones.length; i++) {
                dragZones[i].style.border = "1px solid black"; // Consistent style setting
            }
        });
    }

    
    

    let dragZones = document.getElementsByClassName("dragZone");
    for (const dragZone of dragZones) {
        dragZone.addEventListener("dragover", function(event) {
            event.preventDefault();
            document.getElementById("dropPlaceStatus").textContent = "Valid Drop Location: True"; // Use textContent
        });

        dragZone.addEventListener("dragleave", function(event) {
            event.preventDefault();
            document.getElementById("dropPlaceStatus").textContent = "Valid Drop Location: False"; // Use textContent
        });

        dragZone.addEventListener("drop", function(event) {
            event.preventDefault();
            if (event.target.classList.contains("dragZone")) { // Use classList.contains
                document.getElementById("dropPlaceStatus").textContent = "Valid Drop Location: False";
                let data = event.dataTransfer.getData("Text");
                event.target.appendChild(document.getElementById(data));
            }
        });
    }



    /*
     *
     *
     * Input Events Section
     * 
     * 
     */

    let sliderInput = document.getElementById("slider");
    if (sliderInput) {
        sliderInput.addEventListener("input", function(event) {
            document.getElementById("sliderValue").textContent = "Slider Value: " + event.target.value;
        });

        sliderInput.addEventListener("focus", function() {
            document.getElementById("sliderFocus").textContent = "Slider in Focus: True";
        });
        sliderInput.addEventListener("blur", function() {
            document.getElementById("sliderFocus").textContent = "Slider in Focus: False";
        });
    }

    let textInput = document.getElementById("textInput");
    if (textInput) {
        textInput.addEventListener("input", function(event) {
            document.getElementById("textValue").textContent = "Text Value: " + event.target.value;
            document.getElementById("textAction").textContent = "Text Current Action: Input";
        });
        textInput.addEventListener("focus", function() {
            document.getElementById("textFocus").textContent = "Text Input In Focus: True";
        });
        textInput.addEventListener("blur", function() {
            document.getElementById("textFocus").textContent = "Text Input In Focus: False";
        });
    }
    
    
    


    /* 
    *
    * 
    *   Mouse events
    * 
    * 
     */
    let mouseArea = document.getElementById("mouse-area");
    if (mouseArea) {
        mouseArea.addEventListener("click", function(event) {
            updateMouseStats("click", event);
        });

        mouseArea.addEventListener("contextmenu", function(event) {
            event.preventDefault(); // Prevent default context menu
            updateMouseStats("contextmenu", event);
        });

        mouseArea.addEventListener("dblclick", function(event) {
            updateMouseStats("dblclick", event);
        });

        mouseArea.addEventListener("mousedown", function(event) {
            updateMouseStats("mousedown", event);
        });

        mouseArea.addEventListener("mouseenter", function(event) {
            updateMouseStats("mouseenter", event);
        });

        mouseArea.addEventListener("mouseleave", function(event) {
            updateMouseStats("mouseleave", event);
        });

        mouseArea.addEventListener("mousemove", function(event) {
            updateMouseStats("mousemove", event);
        });

        mouseArea.addEventListener("mouseout", function(event) {
            updateMouseStats("mouseout", event);
        });

        mouseArea.addEventListener("mouseover", function(event) {
            updateMouseStats("mouseover", event);
        });

        mouseArea.addEventListener("mouseup", function(event) {
            updateMouseStats("mouseup", event);
        });
    }

    function updateMouseStats(eventType, event) {
        document.getElementById("event-type").textContent = "Event Type: " + eventType;
        document.getElementById("mouse-position").textContent = "Mouse Position: (" + event.clientX + ", " + event.clientY + ")";
    }
    
    
    
    
    /*
     *
     * Focus Events
     * 
     * 
     */
    let focusArea = document.getElementById("focus-area");
    if (focusArea) {
        focusArea.addEventListener("focus", function() {
            document.getElementById("focusStatus").textContent = "Focus: In";
        });

        focusArea.addEventListener("blur", function() {
            document.getElementById("focusStatus").textContent = "Focus: Out";
        });

        focusArea.addEventListener("focusin", function(event) {
            document.getElementById("focusInTarget").textContent = "Focus In Target: " + event.target.id;
            if (event.relatedTarget) {
                document.getElementById("relatedTarget").textContent = "Related Target: " + event.relatedTarget.id;
            } else {
                document.getElementById("relatedTarget").textContent = "Related Target: null";
            }
        });

        focusArea.addEventListener("focusout", function(event) {
            document.getElementById("focusOutTarget").textContent = "Focus Out Target: " + event.target.id;
            if (event.relatedTarget) {
                document.getElementById("relatedTarget").textContent = "Related Target: " + event.relatedTarget.id;
            } else {
                document.getElementById("relatedTarget").textContent = "Related Target: null";
            }
        });
    }
});