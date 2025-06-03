// Sidebarni ochish va yopish
const toggleButton = document.getElementById('toggleSidebar'); // ☰ tugmasi
const sidebar = document.getElementById('sidebar'); // Sidebar element

toggleButton.addEventListener('click', function() {
    sidebar.classList.toggle('active'); // Sidebarni ochish va yopish
});
