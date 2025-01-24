// Dynamically Load the Navbar
document.addEventListener('DOMContentLoaded', () => {
    // Change the path if your folder structure differs.
    fetch('/components/navbar.html')
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch the navbar.');
            }
            return response.text();
        })
        .then(data => {
            // Insert the fetched navbar HTML into the element with id "navbar"
            const container = document.getElementById('navbar');
            if (container) {
                container.innerHTML = data;
            } else {
                console.error('No element with id "navbar" found.');
            }
        })
        .catch(error => {
            console.error('Error loading navbar:', error);
        });
});

function myFunction() {
    var x = document.getElementById("mynavbar");
    if (x.className === "navbar") {
        x.className += " responsive";
    } else {
        x.className = "navbar";
    }
    var y = document.getElementById("dropdown-wrap");
    if(y.classList.length === 0)
    {
        y.className += "dropmenu-button-make-it-wide-on-click";
    } else {
        y.className = "";
    }
}
