<?php
/*
Plugin Name: NavBar Personalizada Loopian
Description: A plugin to create a custom course menu with categories and courses.
Version: 0.1
Author: Luca Gaido
*/

// Evita el acceso directo
if ( !defined('ABSPATH') ) {
    exit;
}

// Registra el shortcode
function custom_course_menu_shortcode() {
    // Obtén las categorías de cursos
    $categories = get_terms(array(
        'taxonomy' => 'course_category',
        'hide_empty' => false,
    ));

    ob_start();
    ?>
    <div class="custom-course-menu">
        <ul class="course-categories">
            <?php foreach ($categories as $category): ?>
                <li class="category-item">
                    <a href="<?php echo get_term_link($category); ?>"><?php echo $category->name; ?></a>
                    <ul class="course-list">
                        <?php
                        $args = array(
                            'post_type' => 'course',
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'course_category',
                                    'field'    => 'term_id',
                                    'terms'    => $category->term_id,
                                ),
                            ),
                        );
                        $courses = new WP_Query($args);
                        if ($courses->have_posts()): while ($courses->have_posts()): $courses->the_post();
                            ?>
                            <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                        <?php endwhile; wp_reset_postdata(); endif; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('custom_course_menu', 'custom_course_menu_shortcode');

// Agrega los estilos CSS
function custom_course_menu_styles() {
    ?>
    <style>
        .custom-course-menu {
            position: relative;
        }

        .course-categories,
        .course-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .course-categories > .category-item {
            position: relative;
            margin-bottom: 10px;
        }

        .course-categories > .category-item > a {
            display: block;
            padding: 10px;
            background: #f5f5f5;
            text-decoration: none;
            border: 1px solid #ddd;
            color: #333;
        }

        .course-categories > .category-item:hover > .course-list {
            display: block;
        }

        .course-list {
            display: none;
            position: absolute;
            left: 100%;
            top: 0;
            width: 250px;
            max-height: 300px;
            overflow-y: auto;
            background: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .course-list li {
            margin: 0;
        }

        .course-list li a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #333;
            border-bottom: 1px solid #ddd;
        }

        .course-list li a:hover {
            background: #f5f5f5;
        }
    </style>
    <?php
}

add_action('wp_head', 'custom_course_menu_styles');
