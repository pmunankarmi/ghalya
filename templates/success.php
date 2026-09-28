<?php
/**
 * Template Name: Ghalya Application Success
 */

$page_id = get_queried_object_id();
$content = function_exists('get_field') ? get_field('ghalya_success_content', $page_id) : array();
$content = is_array($content) ? $content : array();

get_header();
get_template_part('template-parts/screens/success', null, array(
    'content' => $content,
));
get_footer();
