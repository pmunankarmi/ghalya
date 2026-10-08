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
$selected_tier = array();
$selected_tier_index = 0;
$tiers = array();

if ($screen === 'tier' && function_exists('get_field')) {
    $language = get_post_meta($page_id, '_ghalya_language', true);

    if (!in_array($language, array('en', 'ar'), true) && function_exists('pll_get_post_language')) {
        $language = pll_get_post_language($page_id, 'slug');
    }

    $language = in_array($language, array('en', 'ar'), true) ? $language : 'en';
    $page_ids = get_option('ghalya_page_ids', array());
    $home_id = isset($page_ids[$language]['home']) ? absint($page_ids[$language]['home']) : 0;

    if (!$home_id) {
        $home_id = absint(get_option('page_on_front'));

        if ($home_id && function_exists('pll_get_post')) {
            $translated_home_id = pll_get_post($home_id, $language);
            $home_id = $translated_home_id ? absint($translated_home_id) : $home_id;
        }
    }

    $home_content = $home_id ? get_field('ghalya_home_content', $home_id) : array();
    $tiers = is_array($home_content) && !empty($home_content['tiers']) && is_array($home_content['tiers']) ? $home_content['tiers'] : array();

    $saved_application = isset($_COOKIE['mt_ghalya_application']) ? wp_unslash($_COOKIE['mt_ghalya_application']) : '';
    $application = array();

    if ($saved_application !== '' && strlen($saved_application) <= 100000) {
        if (strpos($saved_application, '%7B') === 0 || strpos($saved_application, '%7b') === 0) {
            $saved_application = rawurldecode($saved_application);
        }

        $decoded_application = json_decode($saved_application, true, 20);
        $application = is_array($decoded_application) ? $decoded_application : array();
    }

    $selected_tier = ghalya_assigned_tier($language, $application, $tiers);
    $matched_tier_index = array_search($selected_tier, $tiers, true);

    if ($matched_tier_index !== false) {
        $selected_tier_index = (int) $matched_tier_index;
    }
}

get_header();
get_template_part('template-parts/screens/application', null, array(
    'screen' => $screen,
    'content' => $content,
    'selected_tier' => $selected_tier,
    'selected_tier_index' => $selected_tier_index,
    'tiers' => $tiers,
));
get_footer();
