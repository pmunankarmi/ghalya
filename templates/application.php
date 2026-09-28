<?php
/**
 * Template Name: Ghalya Application Step
 */

$screen = ghalya_current_screen();
$allowed_screens = array('profile', 'tier', 'work', 'proposal', 'contact');

if (!in_array($screen, $allowed_screens, true)) {
    $screen = 'profile';
}

get_header();
get_template_part('template-parts/screens/application', null, array(
    'screen' => $screen,
    'content' => ghalya_screen_content($screen),
));
get_footer();
