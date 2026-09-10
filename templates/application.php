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
ghalya_render_screen($screen);
get_footer();

