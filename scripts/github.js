document.addEventListener('DOMContentLoaded', () => {
    // First, fetch and insert the footer.
    fetch('./components/footer.html')
        .then(response => response.text())
        .then(data => {
            // Insert the footer HTML into the element with id="footer"
            const footerElement = document.getElementById('footer');
            footerElement.innerHTML = data;

            // Now that the footer is in place, fetch the latest GitHub commit.
            const apiUrl = 'https://api.github.com/repos/stslaug/seanslaughter.dev/commits?per_page=1';
            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    if (data) { // data[0] should be the latest commit
                        const latestCommit = data[0];
                        const commitSha = latestCommit.sha.substring(0, 7); // short SHA
                        const commitUrl = latestCommit.html_url;           // link to commit on GitHub

                        // Update the element with id "latest-commit" in your fetched footer.
                        document.getElementById('latest-commit').innerHTML = `
            <p id="commit">
              Latest Commit: <a style="color:white;" href="${commitUrl}" target="_blank">${commitSha}</a>
            </p>
          `;
                    }
                })
                .catch(error => {
                    console.error('Error fetching latest commit:', error);
                    // Set an error message if fetching commit fails.
                    document.getElementById('latest-commit').innerText = 'Could not load commit.';
                });
        })
        .catch(error => {
            console.error('Error fetching footer:', error);
        });
});

