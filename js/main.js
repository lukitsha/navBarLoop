document.addEventListener('DOMContentLoaded', function () {
    const coursesMenuItem = document.querySelector('.loopian-navbar-courses-menu-item');
    const loopianMenuItem = document.querySelector('.loopian-navbar-loopian-menu-item');
    const dynamicMenu = document.querySelector('.loopian-navbar-fullscreen-panel .loopian-navbar-custom-dynamic-course-menu');
    const loopianMenu = document.querySelector('.loopian-navbar-fullscreen-panel.loopian-navbar-loopian-panel');
    const loadingElement = document.querySelector('.loopian-navbar-course-list-column .loopian-navbar-loading');
    const courseList = document.querySelector('.loopian-navbar-course-list');
    const viewAllButtonContainer = document.querySelector('.loopian-navbar-view-all-button');
    const viewAllButton = viewAllButtonContainer.querySelector('a');

    let allCourses = [];

    // Evento de clic para abrir el menú de "Cursos"
    coursesMenuItem.addEventListener('click', function (e) {
        e.preventDefault();
        dynamicMenu.parentElement.classList.toggle('active');
        coursesMenuItem.classList.toggle('open');
        loadingElement.style.display = 'block';
        courseList.innerHTML = '';

        if (allCourses.length === 0) {
            fetchCourses();
        } else {
            loadingElement.style.display = 'none';
            document.querySelector('.loopian-navbar-category-item').click();
        }
    });

    // Evento de clic para abrir el menú Loopian (hamburguesa)
    loopianMenuItem.addEventListener('click', function (e) {
        e.preventDefault();
        loopianMenu.classList.toggle('active');
        loopianMenuItem.classList.toggle('open');
    });

    document.addEventListener('click', function (e) {
        // Cerrar el menú de cursos si se hace clic fuera
        if (!dynamicMenu.contains(e.target) && !coursesMenuItem.contains(e.target)) {
            dynamicMenu.parentElement.classList.remove('active');
            coursesMenuItem.classList.remove('open');
        }
        // Cerrar el menú Loopian si se hace clic fuera
        if (!loopianMenu.contains(e.target) && !loopianMenuItem.contains(e.target)) {
            loopianMenu.classList.remove('active');
            loopianMenuItem.classList.remove('open');
        }
    });

    // Función para mostrar los cursos de la categoría seleccionada
    function displayCoursesByCategory(categoryId) {
        console.log(`categoryId: ${categoryId}`); // Depuración

        const filteredCourses = allCourses.filter(course => course.category_id == categoryId);
        courseList.innerHTML = '';

        filteredCourses.forEach(course => {
            courseList.innerHTML += `<div class="loopian-navbar-course-item"><a href="${course.link}">${course.title}</a></div>`;
        });

        // Actualiza el objeto con los IDs reales de las categorías y sus URLs correspondientes
        const categoryLinks = {
            324: 'https://www.loopian.com.ar/cursos-administrativos/',
            325: 'https://www.loopian.com.ar/complementos-profesionales/',
            326: 'https://www.loopian.com.ar/diseno-y-comunicacion/',
            327: 'https://www.loopian.com.ar/educacion/',
            328: 'https://www.loopian.com.ar/juridicos-y-contables/',
            329: 'https://www.loopian.com.ar/cursos-programacion/',
            330: 'https://www.loopian.com.ar/oficios/',
            331: 'https://www.loopian.com.ar/turismo-y-hoteleria/',
            332: 'https://www.loopian.com.ar/salud/'
        };

        const viewAllLink = categoryLinks[categoryId]; // Obtiene el enlace correspondiente a la categoría

        if (viewAllLink) {
            viewAllButton.setAttribute('href', viewAllLink); // Asigna el href correcto al botón
            viewAllButtonContainer.style.display = 'block'; // Asegura que el botón sea visible

            // Redirigir al hacer clic
            viewAllButton.onclick = function (e) {
                console.log(`Clickeé el botón de la categoría con ID ${categoryId}`);
                window.location.href = viewAllLink;
            };
        } else {
            console.error(`No se encontró un enlace para la categoría con ID ${categoryId}.`);
            viewAllButtonContainer.style.display = 'none'; // Oculta el botón si no hay enlace para la categoría
        }
    }

    // Función para obtener los cursos mediante AJAX
    function fetchCourses() {
        fetch(ajax_object.ajax_url + '?action=get_all_courses')
            .then(response => response.json())
            .then(data => {
                loadingElement.style.display = 'none';
                if (data.success) {
                    allCourses = data.data.courses;
                    const categoryItems = document.querySelectorAll('.loopian-navbar-category-item');

                    categoryItems.forEach(item => {
                        item.addEventListener('click', function (e) {
                            e.preventDefault();
                            const categoryId = this.getAttribute('data-category-id');
                            displayCoursesByCategory(categoryId);
                        });
                    });

                    // Mostrar la primera categoría y sus cursos al inicio
                    if (categoryItems.length > 0) {
                        categoryItems[0].click();
                    }
                } else {
                    courseList.innerHTML = '<div>No se encontraron cursos.</div>';
                }
            })
            .catch(error => {
                console.error('Error fetching courses:', error);
            });
    }
});
