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
$tiers = array();

if ($screen === 'tier' && function_exists('get_field')) {
    $language = get_post_meta($page_id, '_ghalya_language', true);

    if (!in_array($language, array('en', 'ar'), true) && function_exists('pll_get_post_language')) {
        $language = pll_get_post_language($page_id, 'slug');
    }

    $language = in_array($language, array('en', 'ar'), true) ? $language : 'en';
    $page_ids = get_option('ghalya_page_ids', array());
    $home_id = isset($page_ids[$language]['home']) ? absint($page_ids[$language]['home']) : 0;
    $home_content = $home_id ? get_field('ghalya_home_content', $home_id) : array();
    $tiers = is_array($home_content) && !empty($home_content['tiers']) && is_array($home_content['tiers']) ? $home_content['tiers'] : array();

    foreach ($tiers as $tier) {
        if (!empty($tier['active'])) {
            $selected_tier = $tier;
            break;
        }
    }

    if (!$selected_tier && $tiers) {
        $selected_tier = reset($tiers);
    }

    $saved_application = isset($_COOKIE['mt_ghalya_application']) ? wp_unslash($_COOKIE['mt_ghalya_application']) : '';

    if ($saved_application !== '' && strlen($saved_application) <= 100000) {
        $application = json_decode($saved_application, true, 20);
        $profile_key = $language . ':mt-step-1';
        $profile = is_array($application) && isset($application[$profile_key]) && is_array($application[$profile_key]) ? $application[$profile_key] : array();
        $followers = isset($profile['followers']) ? sanitize_text_field($profile['followers']) : '';
        $categories = isset($profile['content_categories']) && is_array($profile['content_categories']) ? array_map('sanitize_text_field', $profile['content_categories']) : array();
        $generic_match = array();

        foreach ($tiers as $tier) {
            if (($tier['profile_value'] ?? '') !== $followers) {
                continue;
            }

            $configured_categories = array_filter(array_map('trim', explode(',', (string) ($tier['category_values'] ?? ''))));

            if (!$configured_categories) {
                $generic_match = $tier;
                continue;
            }

            if (array_intersect($categories, $configured_categories)) {
                $selected_tier = $tier;
                $generic_match = array();
                break;
            }
        }

        if ($generic_match) {
            $selected_tier = $generic_match;
        }
    }
}

get_header();
get_template_part('template-parts/screens/application', null, array(
    'screen' => $screen,
    'content' => $content,
    'selected_tier' => $selected_tier,
    'tiers' => $tiers,
));
get_footer();
