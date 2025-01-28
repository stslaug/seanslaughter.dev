const colors = new Map();
colors.set('red', 0);
colors.set('green', 0);
colors.set('blue', 0);

const colorHover = new Map();
colorHover.set('red', 0);
colorHover.set('green', 0);
colorHover.set('blue', 0);

let change = 0;

function backgroundBtnClick(color = "red")
{
    let bodyElement = document.getElementsByTagName("body")[0];
    if(bodyElement.style.backgroundColor !== color)
    {
        document.getElementById('background-change').innerHTML = "Background Color Change(s): " + ++change;
        bodyElement.style.backgroundColor = color;
        if(color !== "white") {
            statsColor("white");
        } else {
            statsColor("black");
        }
    }

    document.getElementById(color + "-press").innerHTML = color.charAt(0).toUpperCase() + color.slice(1) + " Button" +
        " Pressed: " +
        " " + updateColor(color);


}

function hoverButton(color = "red")
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

}