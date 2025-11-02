// Sidebar toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const hamburgerIcon = document.getElementById('hamburger-icon');
    const sidebar = document.getElementById('sidebar');

    if (hamburgerIcon && sidebar) {
        hamburgerIcon.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }

    // Dynamic menu loading
    fetch('api/menu.php')
        .then(response => response.json())
        .then(data => {
            const menuList = document.getElementById('menu-list');
            if (menuList) {
                menuList.innerHTML = ''; // Clear existing menu items
                data.forEach(item => {
                    const li = document.createElement('li');
                    const a = document.createElement('a');
                    a.href = `?tool=${encodeURIComponent(item.url)}`;
                    a.textContent = item.name;
                    li.appendChild(a);
                    menuList.appendChild(li);
                });
            }
        })
        .catch(error => {
            console.error('Error loading menu:', error);
        });
});
