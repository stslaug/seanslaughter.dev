
// Dynamically Load the Navbar
document.addEventListener('DOMContentLoaded', () => {
    fetch("./components/navbar.html")
        .then(response => response.text())
        .then(data => {
            document.getElementById('navbar').innerHTML = data;
        });
});

// Dynamically Load the Footer
document.addEventListener('DOMContentLoaded', () => {
    fetch('./components/footer.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('footer').innerHTML = data;
        });
});

// Dynamically load an 'about me' component
document.addEventListener('DOMContentLoaded', () => {
    fetch('./components/about.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('aboutme').innerHTML = data;
        });
});


document.addEventListener('DOMContentLoaded', () => {
    fetch('./components/skills.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('myskills').innerHTML = data;
        });
});

