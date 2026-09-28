<?php
/**
 * Template Name: Ghalya Application Step
 */

$page_id = get_queried_object_id();
$screen = get_post_meta($page_id, '_ghalya_screen', true);
$allowed_screens = array('profile', 'tier', 'work', 'proposal', 'contact');

if (!in_array($screen, $allowed_screens, true)) {
    $screen = 'profile';
}

$content = function_exists('get_field') ? get_field('ghalya_' . $screen . '_content', $page_id) : array();
$content = is_array($content) ? $content : array();

get_header();
get_template_part('template-parts/screens/application', null, array(
    'screen' => $screen,
    'content' => $content,
));
get_footer();
