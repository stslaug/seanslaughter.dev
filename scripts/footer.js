document.addEventListener('DOMContentLoaded', () => {
    fetch('/components/footer.html')
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch the footer.');
            }
            return response.text();
        })
        .then(data => {
            // Insert the fetched navbar HTML into the element with id "navbar"
            const container = document.getElementById('footer');
            if (container) {
                container.innerHTML = data;
            } else {
                console.error('No element with id "footer" found.');
            }
        })
        .catch(error => {
            console.error('Error loading footer:', error);
        });
});




