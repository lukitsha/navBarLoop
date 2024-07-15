document.addEventListener('DOMContentLoaded', function () {
    const coursesMenuItem = document.querySelector('.courses-menu-item');
    const dynamicMenu = document.querySelector('.custom-dynamic-course-menu');
    const loadingElement = document.querySelector('.course-list-column .loading');
    const courseList = document.querySelector('.course-list');

    let allCourses = [];

    coursesMenuItem.addEventListener('mouseover', function (e) {
        dynamicMenu.style.display = 'flex';
        loadingElement.style.display = 'block';
        courseList.innerHTML = '';

        if (allCourses.length === 0) {
            fetchCourses();
        } else {
            loadingElement.style.display = 'none';
            document.querySelector('.category-item').click();
        }
    });

    dynamicMenu.addEventListener('mouseleave', function (e) {
        dynamicMenu.style.display = 'none';
    });

    function fetchCourses() {
        fetch(ajax_object.ajax_url + '?action=get_all_courses')
            .then(response => response.json())
            .then(data => {
                loadingElement.style.display = 'none';
                if (data.success) {
                    allCourses = data.data.courses;
                    const categoryItems = document.querySelectorAll('.category-item');

                    categoryItems.forEach(item => {
                        item.addEventListener('click', function (e) {
                            e.preventDefault();
                            const categoryId = this.getAttribute('data-category-id');
                            const filteredCourses = allCourses.filter(course => course.category_id == categoryId);
                            courseList.innerHTML = '';
                            filteredCourses.forEach(course => {
                                courseList.innerHTML += '<div class="course-item"><a href="' + course.link + '">' + course.title + '</a></div>';
                            });
                            // Marcar la categoría activa
                            categoryItems.forEach(cat => cat.classList.remove('active'));
                            this.classList.add('active');
                        });
                    });

                    // Mostrar la primera categoría y sus cursos
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
