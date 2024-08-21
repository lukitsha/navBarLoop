<?php
/*
Plugin Name: Navbar Personalizada Loopian
Description: A plugin to create a custom course menu with categories and courses.
Version: 0.8.6
Author: Luca Gaido
*/

// Evita el acceso directo
if (!defined('ABSPATH')) {
    exit;
}

// Incluye los estilos y scripts
function custom_dynamic_course_menu_enqueue_scripts() {
    wp_enqueue_style('loopian-navbar-styles', plugin_dir_url(__FILE__) . 'css/styles.css');
    wp_enqueue_script('loopian-navbar-scripts', plugin_dir_url(__FILE__) . 'js/script.js', array('jquery'), null, true);

    // Pasa la URL de AJAX al script
    wp_localize_script('loopian-navbar-scripts', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'custom_dynamic_course_menu_enqueue_scripts');

// Registra el shortcode
function custom_dynamic_course_menu_shortcode() {
    // Obtén las categorías de cursos
    $categories = get_terms(array(
        'taxonomy' => 'categoria-curso',
        'hide_empty' => false,
    ));

    // Verifica si hay errores
    if (is_wp_error($categories)) {
        return 'Error al obtener las categorías de cursos.';
    }

    ob_start();
    ?>
    <div class="loopian-navbar-custom-menu">
        <div class="loopian-navbar-main-menu">
            <div class="loopian-navbar-logo" style="margin-top: 10px;">
                <a href="<?php echo home_url(); ?>">
                    <img src="https://www.loopian.com.ar/cursos/wp-content/uploads/2020/12/logo14.png" height="50px" alt="Logo">
                </a>
            </div>
            <div class="loopian-navbar-menu-items">
                <div><a href="<?php echo home_url(); ?>">Home</a></div>
                <div class="loopian-navbar-courses-menu-item"><a href="#">Cursos</a></div>
                <div class="loopian-navbar-loopian-menu-item">
                    <a href="#"><i class="fas fa-bars"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="loopian-navbar-fullscreen-panel">
        <div class="loopian-navbar-custom-dynamic-course-menu">
            <div class="loopian-navbar-course-categories-column">
                <div class="loopian-navbar-course-categories">
                    <?php foreach ($categories as $category): ?>
                        <div class="loopian-navbar-category-item" data-category-id="<?php echo esc_attr($category->term_id); ?>">
                            <a href="#"><?php echo esc_html($category->name); ?></a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="loopian-navbar-course-list-column">
                <div class="loopian-navbar-loading" style="display: none;">Cargando...</div>
                <div class="loopian-navbar-course-list"></div>
                <!-- Botón "ver todos" ahora se muestra en la columna de cursos -->
                <div class="loopian-navbar-view-all-button">
                    <a href="#" id="view-all-courses-btn">Ver Todos</a>
                </div>
            </div>
        </div>
        <!-- Botón de WhatsApp debajo del menú de cursos -->
        <div class="loopian-navbar-whatsapp-button">
            <a href="https://wa.me/5493512620001?text=Hola!%20me%20interesa%20recibir%20más%20información%20sobre%20los%20cursos%20de%20Loopian!" target="_blank">
                <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp" height="30px">
                Contáctanos
            </a>
        </div>
    </div>
    <div class="loopian-navbar-fullscreen-panel loopian-navbar-loopian-panel">
        <div class="loopian-navbar-custom-loopian-menu">
            <div class="loopian-navbar-loopian-menu-column">
                <div class="loopian-navbar-loopian-menu">
                    <div class="loopian-navbar-loopian-item"><a href="<?php echo home_url('/metodo-loopian'); ?>">Método Loopian</a></div>
                    <div class="loopian-navbar-loopian-item"><a href="<?php echo home_url('/testimonios-de-alumnos'); ?>">Testimonio de Alumnos</a></div>
                    <div class="loopian-navbar-loopian-item"><a href="<?php echo home_url('/contacto'); ?>">Contacto</a></div>
                    <div class="loopian-navbar-loopian-item"><a href="<?php echo home_url('/campus/login/index.php'); ?>">Campus</a></div>
                </div>
                <div class="loopian-navbar-social-icons">
                    <a href="https://www.tiktok.com/@loopiancursos" class="social-icon social-tiktok" title="TikTok" target="_blank">
                        <img src="https://www.svgrepo.com/show/327400/logo-tiktok.svg" alt="TikTok" class="social-svg-icon">
                    </a>
                    <a href="https://www.instagram.com/loopianeducacion/" class="social-icon social-instagram" title="Instagram" target="_blank">
                        <img src="https://www.svgrepo.com/show/521711/instagram.svg" alt="Instagram" class="social-svg-icon">
                    </a>
                    <a href="https://www.facebook.com/loopian" class="social-icon social-facebook" title="Facebook" target="_blank">
                        <img src="https://www.svgrepo.com/show/521654/facebook.svg" alt="Facebook" class="social-svg-icon">
                    </a>
                    <a href="https://ar.linkedin.com/company/loopian---centro-de-educaci%C3%B3n-a-distancia" class="social-icon social-linkedin" title="LinkedIn" target="_blank">
                        <img src="https://www.svgrepo.com/show/521725/linkedin.svg" alt="LinkedIn" class="social-svg-icon">
                    </a>
                </div>
                <!-- Botón de WhatsApp debajo de los íconos sociales en el menú hamburguesa -->
                <div class="loopian-navbar-whatsapp-button">
                    <a href="https://wa.me/5493512620001?text=Hola!%20me%20interesa%20recibir%20más%20información%20sobre%20los%20cursos%20de%20Loopian!" target="_blank">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp" height="30px">
                        Contáctanos
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php
    return trim(ob_get_clean());
}

add_shortcode('custom_dynamic_course_menu', 'custom_dynamic_course_menu_shortcode');

// Maneja la solicitud AJAX
function get_all_courses() {
    $categories = get_terms(array(
        'taxonomy' => 'categoria-curso',
        'hide_empty' => false,
    ));

    if (is_wp_error($categories)) {
        wp_send_json_error();
    }

    $categories_data = array();
    foreach ($categories as $category) {
        $categories_data[] = array(
            'id' => $category->term_id,
            'name' => $category->name,
        );
    }

    $courses = get_posts(array(
        'post_type' => 'curso',
        'posts_per_page' => -1,
    ));

    $courses_data = array();
    foreach ($courses as $course) {
        $course_categories = wp_get_post_terms($course->ID, 'categoria-curso');
        if (!empty($course_categories)) {
            $courses_data[] = array(
                'title' => get_the_title($course),
                'link' => get_permalink($course),
                'category_id' => $course_categories[0]->term_id,
            );
        }
    }

    wp_send_json_success(array(
        'categories' => $categories_data,
        'courses' => $courses_data,
    ));

    wp_die();
}

add_action('wp_ajax_get_all_courses', 'get_all_courses');
add_action('wp_ajax_nopriv_get_all_courses', 'get_all_courses');
