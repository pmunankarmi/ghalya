<?php
/**
 * Template Name: Ghalya Terms
 */

get_header();
get_template_part('template-parts/screens/terms', null, array(
    'screen' => 'terms',
    'content' => ghalya_screen_content('terms'),
));
get_footer();
