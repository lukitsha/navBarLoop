// Añadir el shortcode
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
