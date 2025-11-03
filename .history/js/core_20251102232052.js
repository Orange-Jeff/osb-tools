// Dynamic tool loading functionality
document.addEventListener('DOMContentLoaded', function() {
    // Function to load tool content from URL
    function loadToolFromUrl(toolUrl) {
        const contentDiv = document.getElementById('content');
        if (!contentDiv) return;

        // Show loading state
        contentDiv.innerHTML = '<p>Loading tool...</p>';

        // Fetch tool HTML from the specified URL
        fetch(toolUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(html => {
                contentDiv.innerHTML = html;
                // Update URL hash for bookmarkability
                window.location.hash = `#tool=${encodeURIComponent(toolUrl)}`;
            })
            .catch(error => {
                console.error('Error loading tool:', error);
                contentDiv.innerHTML = '<p>Error loading tool. Please try again.</p>';
            });
    }

    // Handle menu item clicks
    document.addEventListener('click', function(e) {
        if (e.target.tagName === 'A' && e.target.closest('#menu-list')) {
            e.preventDefault();
            const href = e.target.getAttribute('href');
            const urlParams = new URLSearchParams(href.substring(1)); // Remove '?'
            const toolUrl = urlParams.get('tool');
            if (toolUrl) {
                loadToolFromUrl(toolUrl);
            }
        }
    });

    // Load tool from URL hash on page load
    const hash = window.location.hash;
    if (hash.startsWith('#tool=')) {
        const toolName = decodeURIComponent(hash.substring(6)); // Remove '#tool='
        loadTool(toolName);
    }
});
