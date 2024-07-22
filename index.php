<?php
/*
Plugin Name: Navbar Personalizada 
Description: A plugin to create a custom course menu with categories and courses.
Version: 0.2.2
Author: Luca Gaido
*/

// Evita el acceso directo
if (!defined('ABSPATH')) {
    exit;
}

// Incluye los estilos y scripts
function custom_dynamic_course_menu_enqueue_scripts() {
    wp_enqueue_style('custom-dynamic-course-menu-styles', plugin_dir_url(__FILE__) . 'css/styles.css');
    wp_enqueue_script('custom-dynamic-course-menu-scripts', plugin_dir_url(__FILE__) . 'js/script.js', array('jquery'), null, true);

    // Pasa la URL de AJAX al script
    wp_localize_script('custom-dynamic-course-menu-scripts', 'ajax_object', array(
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
    <div class="custom-menu">
        <div class="main-menu">
            <div class="logo"><a href="<?php echo home_url(); ?>"><img src="<?php echo plugin_dir_url(__FILE__) . 'images/logo.png'; ?>" alt="Logo"></a></div>
            <div class="menu-items">
                <div><a href="<?php echo home_url(); ?>">Home</a></div>
                <div class="courses-menu-item"><a href="#">Cursos</a></div>
                <div><a href="<?php echo home_url('/contacto'); ?>">Contacto</a></div>
                <div><a href="<?php echo home_url('/aula-virtual'); ?>">Aula Virtual</a></div>
            </div>
            <div class="menu-toggle"><span>&#9776;</span></div>
        </div>
    </div>
    <div class="fullscreen-panel">
        <button class="close-button">&times;</button>
        <div class="custom-dynamic-course-menu">
            <div class="course-categories-column">
                <div class="course-categories">
                    <?php foreach ($categories as $category): ?>
                        <div class="category-item" data-category-id="<?php echo esc_attr($category->term_id); ?>">
                            <a href="#"><?php echo esc_html($category->name); ?></a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="course-list-column">
                <div class="loading" style="display: none;">Cargando...</div>
                <div class="course-list"></div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
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
?>
