// Sidebar toggle and active state management
document.addEventListener('DOMContentLoaded', function() {
    const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');
    const currentPath = window.location.pathname;

    // Set active state based on current URL
    allSideMenu.forEach(item => {
        const li = item.parentElement;
        
        // Check if the href matches the current path
        if (item.getAttribute('href') === currentPath || 
            (currentPath.includes('alumni') && item.getAttribute('href').includes('alumni')) ||
            (currentPath.includes('contacts') && item.getAttribute('href').includes('contacts')) ||
            (currentPath.includes('departments') && item.getAttribute('href').includes('departments')) ||
            (currentPath.includes('reports') && item.getAttribute('href').includes('reports')) ||
            // Add student routes
            (currentPath.includes('student/profile') && item.getAttribute('href').includes('student/profile')) ||
            (currentPath.includes('student/contacts') && item.getAttribute('href').includes('student/contacts')) ||
            (currentPath === '/' && item.getAttribute('href') === '/')) {
            
            // Remove active class from all items
            allSideMenu.forEach(i => i.parentElement.classList.remove('active'));
            // Add active class to current item
            li.classList.add('active');
        }

        // Handle click events
        item.addEventListener('click', function () {
            allSideMenu.forEach(i => {
                i.parentElement.classList.remove('active');
            })
            li.classList.add('active');
        });
    });

    // Menu toggle
    const menuBar = document.querySelector('#content nav .bx.bx-menu');
    const sidebar = document.getElementById('sidebar');
    menuBar.addEventListener('click', function () {
        sidebar.classList.toggle('hide');
    });
}); 