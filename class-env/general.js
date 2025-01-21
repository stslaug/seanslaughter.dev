
// Dynamically Load the Navbar
document.addEventListener('DOMContentLoaded', () => {
    fetch('./components/navbar.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('navbar').innerHTML = data;
        });
});