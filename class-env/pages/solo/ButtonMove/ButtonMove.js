let sum = 0;
let quantity = 0;

/*
       Update Text Section
 */

function updateTotal(num) {
    let count = document.getElementById("buttonSum");
    sum += num;
    count.innerText = "Value of Clicked Buttons: " + sum;
}


/*
        Make the button Section
 */
let buttons = []

function makeButton() {
    let view = document.getElementById("view");
    let rand = Math.floor(Math.random() * 100 + 1);

    let temp = document.createElement("button");
    buttons.push(temp);

    temp.classList.add("newButtons");
    temp.value = rand;
    temp.textContent = temp.value.toString();

    temp = styleButtons(temp);

    temp.addEventListener("click", function () {

        const value = parseInt(this.value);
        updateTotal(value);
    });

    temp.movingRight = !!(rand % 2);
    temp.movingUp = !!(rand % 2);


    document.getElementById("quantity").innerText = "Number of Buttons: " + (++quantity);

    view.appendChild(temp);

}

//Doing this with Javascript and not CSS since I had to assign color and random start location anyways
function styleButtons(button) {
    let view = document.getElementById("view");

    let color = document.getElementById("colors").value;

    button.classList.add(color);
    button.classList.add("mve-btn");
    button.width = 80;
    button.height = 30;
    button.type = "button";

    let randX = Math.floor(Math.random() * (view.offsetWidth - parseInt(button.offsetWidth)));
    let randY = Math.floor(Math.random() * (view.offsetHeight - parseInt(button.offsetHeight)));


    button.style.top = randY + "px";
    button.style.left = randX + "px";
    return button;
}


/*
       move the Button Section
       Also uses buttons[] and movingButtons from above section
 */
let timerID;
let movingButtons = false;

function changeMove() {
    movingButtons = !movingButtons;
    if (movingButtons) {
        moveButtons();
    } else {
        clearTimeout(timerID);
    }
}


function moveButtons() {
    let view = document.getElementById("view");
    let viewWidth = view.clientWidth;
    let viewHeight = view.clientHeight;

    buttons.forEach(button => {
        let leftPos = parseInt(button.style.left, 10);
        let verticalPos = parseInt(button.style.top, 10);
        let btnWidth = parseInt(button.offsetWidth);
        let btnHeight = parseInt(button.offsetHeight);

        let step = 1; // movement speed (set to 1 so it is easier to click a button)

        if (button.movingRight) {
            leftPos += step;
            if (leftPos >= viewWidth - btnWidth) {
                leftPos = viewWidth - btnWidth;
                button.movingRight = false; // change direction
            }
        } else {
            leftPos -= step;
            if (leftPos <= 0) {
                leftPos = 0;
                button.movingRight = true;
            }
        }
        if (button.movingUp) {
            verticalPos += step;
            if (verticalPos >= viewHeight - btnHeight) {
                verticalPos = viewHeight - btnHeight;
                button.movingUp = false; // change direction
            }
        } else {
            verticalPos -= step;
            if (verticalPos <= 0) {
                verticalPos = 0;
                button.movingUp = true;
            }
        }
        button.style.left = leftPos + 'px';
        button.style.top = verticalPos + 'px';
    });
    if (movingButtons) {
        timerID = setTimeout(moveButtons, 16);
    }

}

