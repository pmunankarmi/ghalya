<?php
/**
 * Template Name: Ghalya Terms
 */

$page_id = get_queried_object_id();
$content = function_exists('get_field') ? get_field('ghalya_terms_content', $page_id) : array();
$content = is_array($content) ? $content : array();

get_header();
get_template_part('template-parts/screens/terms', null, array(
    'content' => $content,
));
get_footer();
