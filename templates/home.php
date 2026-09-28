<?php
/**
 * Template Name: Ghalya Home Page
 */

$content = ghalya_screen_content('home');
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
