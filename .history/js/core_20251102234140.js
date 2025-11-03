// Dynamic tool loading functionality
document.addEventListener('DOMContentLoaded', function() {
  // Function to load tool content from URL
  function loadToolFromUrl(toolUrl) {
    const contentDiv = document.getElementById("content");
    if (!contentDiv) return;

    // Show loading state
    contentDiv.innerHTML = "<p>Loading tool...</p>";

    // Fetch tool HTML from the specified URL
    fetch(toolUrl)
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
      })
      .then((html) => {
        // Use DOMParser to handle full HTML pages gracefully
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, "text/html");
        const toolContent = doc.getElementById("tool-container");

        if (toolContent) {
          // If a specific container is found, inject its content
          contentDiv.innerHTML = toolContent.innerHTML;
        } else {
          // Otherwise, inject the whole HTML (for simple fragment tools)
          contentDiv.innerHTML = html;
        }
        window.location.hash = `#tool=${encodeURIComponent(toolUrl)}`;
      })
      .catch((error) => {
        console.error("Error loading tool:", error);
        contentDiv.innerHTML = "<p>Error loading tool. Please try again.</p>";
      });
  }

  // Handle menu item clicks
  document.addEventListener("click", function (e) {
    if (e.target.tagName === "A" && e.target.closest("#menu-list")) {
      e.preventDefault();
      const href = e.target.getAttribute("href");
      const urlParams = new URLSearchParams(href.substring(1)); // Remove '?'
      const toolUrl = urlParams.get("tool");
      if (toolUrl) {
        loadToolFromUrl(toolUrl);
      }
    }
  });

  // Load tool from URL hash on page load, or default to the ACE editor
  const hash = window.location.hash;
  if (hash.startsWith("#tool=")) {
    const toolName = decodeURIComponent(hash.substring(6)); // Remove '#tool='
    loadToolFromUrl(toolName);
  } else {
    // Default to loading the ACE editor if no tool is specified
    loadToolFromUrl("tools/osb-ace-editor.php");
  }
});
