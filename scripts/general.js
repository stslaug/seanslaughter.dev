
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



document.addEventListener('DOMContentLoaded', () => {
    // Change the path if your folder structure differs.
    fetch('/components/about.html')
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch the about.');
            }
            return response.text();
        })
        .then(data => {
            // Insert the fetched navbar HTML into the element with id "navbar"
            const container = document.getElementById('aboutme');
            if (container) {
                container.innerHTML = data;
            } else {
                console.error('No element with id "aboutme" found.');
            }
        })
        .catch(error => {
            console.error('Error loading aboutme:', error);
        });
});




document.addEventListener('DOMContentLoaded', () => {
    // Change the path if your folder structure differs.
    fetch('/components/skills.html')
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch the skills.');
            }
            return response.text();
        })
        .then(data => {
            // Insert the fetched navbar HTML into the element with id "navbar"
            const container = document.getElementById('myskills');
            if (container) {
                container.innerHTML = data;
            } else {
                console.error('No element with id "myskills" found.');
            }
        })
        .catch(error => {
            console.error('Error loading skills:', error);
        });
});

