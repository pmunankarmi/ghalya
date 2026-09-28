<?php
/**
 * Template Name: Ghalya Home Page
 */

$page_id = get_queried_object_id();
$content = function_exists('get_field') ? get_field('ghalya_home_content', $page_id) : array();
$content = is_array($content) ? $content : array();
$section_args = array(
    'content' => $content,
    'language' => ghalya_current_language(),
    'profile_url' => ghalya_page_url('profile'),
);

get_header();
?>
<main>
  <?php get_template_part('template-parts/home/hero', null, $section_args); ?>
  <?php get_template_part('template-parts/home/partners', null, $section_args); ?>
  <?php get_template_part('template-parts/home/benefits', null, $section_args); ?>
  <?php get_template_part('template-parts/home/deliverables', null, $section_args); ?>
  <?php get_template_part('template-parts/home/eligibility', null, $section_args); ?>
  <?php get_template_part('template-parts/home/faq', null, $section_args); ?>
</main>
<?php
get_footer();
