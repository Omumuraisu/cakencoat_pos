document.getElementById('menu-toggle').addEventListener('click', function () {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('hidden');
    sidebar.classList.toggle('visible');
});

// Close sidebar when clicking the close button
document.getElementById('close-sidebar').addEventListener('click', function () {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.add('hidden');
    sidebar.classList.remove('visible');
});

// Close sidebar when clicking outside of it
document.addEventListener('click', function (event) {
    const sidebar = document.getElementById('sidebar');
    const menuToggle = document.getElementById('menu-toggle');
    if (!sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
        sidebar.classList.add('hidden');
        sidebar.classList.remove('visible');
    }
});

document.querySelectorAll('.category-header').forEach(header => {
    header.addEventListener('click', () => {
        console.log('Header clicked:', header); // Log the clicked header
        const subcategory = header.nextElementSibling; // Get the next sibling element
        if (subcategory && subcategory.classList.contains('subcategory')) {
            console.log('Toggling subcategory:', subcategory); // Log the subcategory being toggled
            subcategory.classList.toggle('hidden'); // Toggle the 'hidden' class
            header.classList.toggle('expanded'); // Optional: Add a class for styling the expanded state
        } else {
            console.log('No subcategory found or incorrect structure.');
        }
    });
});