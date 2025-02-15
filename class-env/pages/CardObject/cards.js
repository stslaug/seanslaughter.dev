let cards = [];
let newCards = [];
let shown= false;

function Card(name, email, address, phone, birth) {
    this.name = name;
    this.email = email;
    this.address = address;
    this.phone = phone;
    this.birth = birth;
    this.printCard = printCard; // Add printCard as a method
    cards.push(this);
    newCards.push(this);
}

function printCard() {
    let cardDiv = document.createElement("div");
    cardDiv.innerHTML += "<strong>Name: </strong>" + this.name + "<br>";
    cardDiv.innerHTML += "<strong>Email: </strong>" + this.email + "<br>";
    cardDiv.innerHTML += "<strong>Address: </strong>" + this.address + "<br>";
    cardDiv.innerHTML += "<strong>Phone: </strong>" + this.phone + "<br>";
    cardDiv.innerHTML += "<strong>Birthday: </strong>" + this.birth + "<hr>";

    let cardsContainer = document.getElementById("cards");
    if (cardsContainer) {
        cardsContainer.appendChild(cardDiv);
    } else {
        console.log("Couldn't find card display location (#cards)");
    }
}

function initCards() {
    new Card("Sue Suthers", "sue@suthers.com", "123 Elm Street, Yourtown ST 99999", "555-555-9876", new Date("1991-08-19"));
    new Card("Fred Fanboy", "fred@fanboy.com", "233 Oak Lane, Sometown ST 99399", "555-555-4444", new Date("1942-08-19"));
    new Card("Jimbo Jones", "jimbo@jones.com", "233 Walnut Circle, Anotherville ST 88999", "555-555-1344", new Date("1942-08-19"));
    new Card("Jimbo Jones", "jimbo@jones.com", "233 Walnut Circle, Anotherville ST 88999", "555-555-1344", new Date("1942-08-19"));

    new Card("Alice Smith", "alice.smith@emailprovider.net", "12 Oak Avenue, Apt 3B, Smallville, CA 91234", "555-867-5309", new Date("1985-03-10"));

    new Card("Jean-Pierre Dubois", "jp.dubois@courriel.fr", "45 Rue de la République, 75010 Paris, France", "+33 1 42 55 67 89", new Date("1970-11-22"));

    new Card("Robert Johnson", "rjohnson@acmeindustries.com", "PO Box 1234, Springfield, IL 62705", "555-555-9000", new Date("1963-07-04"));

    new Card("Maria Garcia Rodriguez", "maria.garcia.rodriguez@correo.mx", "Calle Principal #123, Colonia Centro, Delegacion Cuauhtemoc, 06000 Ciudad de Mexico, Mexico", "555-123-4567", new Date("1992-01-15"));

    new Card("Alex Lee", "alexlee@email.co", "7 Bridge Street, Anytown, State", "555-222-3333", new Date("2001-09-28"));
    newCards = [];
}

document.getElementById('showAll').addEventListener('click', function () {
    if (!shown) {
        shown = !shown;
        document.getElementById('showAll').innerText = "Hide All";
        displayAllCards();
    } else {
        shown = !shown;
        document.getElementById('showAll').innerText = "Show All";
        hideAllCards();
    }

})

function displayAllCards() {
    // Clear the existing cards from the display
    const cardsContainer = document.getElementById("cards");
    while (cardsContainer.firstChild) {
        cardsContainer.removeChild(cardsContainer.firstChild);
    }
    for (let i = 0; i < cards.length; i++) {
        cards[i].printCard();  // Call the printCard method on each card
    }
}

function hideAllCards() {
    // Clear the existing cards from the display
    const cardsContainer = document.getElementById("cards");
    while (cardsContainer.firstChild) {
        cardsContainer.removeChild(cardsContainer.firstChild);
    }
    for (let i = 0; i < newCards.length; i++) {
        newCards[i].printCard();  // Call the printCard method on each card
    }


}

document.getElementById("addCard").addEventListener("click", function () {
    event.preventDefault();
    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const address = document.getElementById("address").value;
    const phone = document.getElementById("phone").value;
    const birth = new Date(document.getElementById("birth").value); // Create Date object

    if (name && email) {
        const newCard = new Card(name, email, address, phone, birth);
        newCard.printCard();

        //clear the input form
        document.getElementById("name").value = "";
        document.getElementById("email").value = "";
        document.getElementById("address").value = "";
        document.getElementById("phone").value = "";
        document.getElementById("birth").value = "";

    } else {
        alert("Name and Email are required");
    }
});

initCards();