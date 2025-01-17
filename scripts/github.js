// Fetches the latest Commit number from my GithubRepo
const apiUrl = 'https://api.github.com/repos/stslaug/seanslaughter.dev/commits?per_page=1';

fetch(apiUrl)
    .then(response => response.json())
    .then(data => {
        // data[0] should be the latest commit
        const latestCommit = data[0];
        const commitSha = latestCommit.sha.substring(0, 7); // short SHA
        const commitUrl = latestCommit.html_url; // link to commit on GitHub

        document.getElementById('latest-commit').innerHTML = `
        <p id="commit">
           Latest Commit: <a id="commit" href="${commitUrl}" target="_blank">${commitSha}</a>
        </p>
      `;
    })
    .catch(error => {
        console.error('Error fetching latest commit:', error);
        document.getElementById('latest-commit').innerText = 'Could not load commit.';
    });