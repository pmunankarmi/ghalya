<?php
/**
 * Template Name: Ghalya Application Success
 */

get_header();
get_template_part('template-parts/screens/success', null, array(
    'screen' => 'success',
    'content' => ghalya_screen_content('success'),
));
get_footer();
