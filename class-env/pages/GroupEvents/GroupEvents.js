/*
 *
 *
 * Animation Events Section
 * 
 * 
 */
let iteration = 0;

document.addEventListener("DOMContentLoaded", function() {
    let colorBox = document.getElementById("color-box");
    if(colorBox)
    {
        colorBox.addEventListener("animationiteration", colorBoxData);
    }

    function colorBoxData(event)
    {
        let time = document.getElementById("elapsed-time");
            if(time)
            {
                time.innerHTML = "Elapsed Time: " + event.elapsedTime;
            }

        let animationName = document.getElementById("animation-name");
        if(animationName)
        {
            animationName.innerHTML = "Animation Name: " + event.animationName;
        }

        let iterations = document.getElementById("iterations");
        if(iterations) {
            iterations.innerHTML = "Iterations: " + ++iteration;
        }
    }

});


/*
 *
 *
 * Drag Events Section
 * 
 * 
 */

function dragStart(event)
{
    event.dataTransfer.dropEffect = "move";
    event.dataTransfer.setData("Text", event.target.id);
    document.getElementById("dragStatus").innerText = "Dragging: True";
    event.target.style.opacity = "1";
    
    let dragZones = document.getElementsByClassName("dragZone");
    
    for(let i = 0; i < dragZones.length; i++)
    {
        dragZones[i].style = "border: 2px dotted green";
    }
}
function dragEnd(event)
{
    event.preventDefault();
    document.getElementById("dragStatus").innerText = "Dragging: False";
    let dragZones = document.getElementsByClassName("dragZone");

    for(let i = 0; i < dragZones.length; i++)
    {
        dragZones[i].style = "border: 1px solid black";
    }
}


function dropOver(event)
{
    event.preventDefault();
    document.getElementById("dropPlaceStatus").innerText = "Valid Drop Location: True";
}
function stopDrop(event)
{
    event.preventDefault();
    document.getElementById("dropPlaceStatus").innerText = "Valid Drop Location: False";
}
function drop(event)
{
    event.preventDefault()
    if(event.target.className === "dragZone")
    {
        stopDrop(event);
        var data = event.dataTransfer.getData("Text");
        event.target.appendChild(document.getElementById(data));
    }
}

/*
 *
 *
 * Input Events Section
 * 
 * 
 */

