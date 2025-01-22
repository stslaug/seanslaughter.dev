
// Dynamically Load the Navbar
document.addEventListener('DOMContentLoaded', () => {
    fetch('./components/navbar.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('navbar').innerHTML = data;
        });
});

function myFunction() {
    var x = document.getElementById("mynavbar");
    if (x.className === "navbar") {
        x.className += " responsive";
    } else {
        x.className = "navbar";
    }
}
