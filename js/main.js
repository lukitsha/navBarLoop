document.addEventListener('DOMContentLoaded', function () {
    // Obtener el panel de cursos y el botón de cierre
    const coursePanel = document.querySelector('.fullscreen-panel');
    const closeButton = document.querySelector('.close-button');
    const menuToggle = document.querySelector('.menu-toggle');
    const menuItems = document.querySelector('.menu-items');

    // Agregar evento para abrir el panel en pantalla completa
    document.querySelector('.open-fullscreen').addEventListener('click', function () {
        coursePanel.classList.add('active');
    });

    // Agregar evento para cerrar el panel de pantalla completa
    closeButton.addEventListener('click', function () {
        coursePanel.classList.remove('active');
    });

    // Agregar evento para el menú hamburguesa
    menuToggle.addEventListener('click', function () {
        menuItems.classList.toggle('active');
    });
});
