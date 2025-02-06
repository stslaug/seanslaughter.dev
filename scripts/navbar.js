// Dynamically Load the Navbar
document.addEventListener('DOMContentLoaded', () => {
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
                loadFontAwesome(); // Ensure FontAwesome is loaded
            } else {
                console.error('No element with id "navbar" found.');
            }
        })
        .catch(error => {
            console.error('Error loading navbar:', error);
        });
});

// Function to check and dynamically load FontAwesome script
function loadFontAwesome() {
    if (!document.querySelector('script[src="https://kit.fontawesome.com/44ecd57f75.js"]')) {
        const script = document.createElement('script');
        script.src = "https://kit.fontawesome.com/44ecd57f75.js";
        script.crossOrigin = "anonymous";
        document.head.appendChild(script);
    }
}


function toggleMenu() {
    const navItems = document.getElementById("nav-items");
    navItems.classList.toggle("active");
}