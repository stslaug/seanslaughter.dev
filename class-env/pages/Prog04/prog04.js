const colors = new Map();
colors.set('indianred', 0);
colors.set('green', 0);
colors.set('royalblue', 0);

const colorHover = new Map();
colorHover.set('indianred', 0);
colorHover.set('green', 0);
colorHover.set('royalblue', 0);

let change = 0;

function backgroundBtnClick(color = "indianred")
{
    let bodyElement = document.getElementsByTagName("body")[0];
    if(bodyElement.style.backgroundColor !== color)
    {
        document.getElementById('background-change').innerHTML = "Background Color Change(s): " + ++change;
        bodyElement.style.backgroundColor = color;
        if(color === "white") {
            statsColor("black");
        } else {
            statsColor("white");
        }
    }

    document.getElementById(color + "-press").innerHTML = color.charAt(0).toUpperCase() + color.slice(1) + " Button" +
        " Pressed: " +
        " " + updateColor(color);


}

function hoverButton(color = "indianred")
{
    document.getElementById(color + "-hover").innerHTML = color.charAt(0).toUpperCase() + color.slice(1) + " Button" +
        " Hovered Over: " + updateColorHover(color);
}

function updateColor(color)
{
    colors.set(color, colors.get(color) + 1);
    return colors.get(color);
}
function updateColorHover(color)
{
    colorHover.set(color, colorHover.get(color) + 1);
    return colorHover.get(color);
}

function statsColor(color = "white")
{
    let stats = document.getElementById("stats-wrapper");
    stats.style.color = color;
}