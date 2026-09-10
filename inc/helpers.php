<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Return the active front-end language without making Polylang mandatory.
 */
function ghalya_current_language()
{
    if (function_exists('pll_current_language')) {
        $language = pll_current_language('slug');

        if (in_array($language, array('en', 'ar'), true)) {
            return $language;
        }
    }

    $page_language = get_post_meta(get_queried_object_id(), '_ghalya_language', true);

    if (in_array($page_language, array('en', 'ar'), true)) {
        return $page_language;
    }

    return strpos(get_locale(), 'ar') === 0 ? 'ar' : 'en';
}

/**
 * Identify the Ghalya screen represented by the current WordPress page.
 */
function ghalya_current_screen()
{
    if (is_front_page()) {
        return 'home';
    }

    $screen = get_post_meta(get_queried_object_id(), '_ghalya_screen', true);
    $allowed = array('home', 'profile', 'tier', 'work', 'proposal', 'contact', 'success', 'terms');

    return in_array($screen, $allowed, true) ? $screen : 'home';
}

/**
 * Resolve a theme screen to its WordPress permalink.
 */
function ghalya_page_url($screen, $language = '')
{
    $language = in_array($language, array('en', 'ar'), true) ? $language : ghalya_current_language();
    $page_ids = get_option('ghalya_page_ids', array());
    $page_id = isset($page_ids[$language][$screen]) ? absint($page_ids[$language][$screen]) : 0;

    if ($page_id && get_post_status($page_id)) {
        return get_permalink($page_id);
    }

    if ($screen === 'home') {
        return home_url('/');
    }

    $slug = $screen . ($language === 'ar' ? '-ar' : '');
    return home_url('/' . $slug . '/');
}

/**
 * Return the URL for the matching page in the other language.
 */
function ghalya_language_switch_url()
{
    $other_language = ghalya_current_language() === 'ar' ? 'en' : 'ar';
    $translated_id = 0;

    if (function_exists('pll_get_post')) {
        $translated_id = pll_get_post(get_queried_object_id(), $other_language);
    }

    if ($translated_id) {
        return get_permalink($translated_id);
    }

    return ghalya_page_url(ghalya_current_screen(), $other_language);
}

/** Return Polylang's configured native name for the other language. */
function ghalya_language_switch_name()
{
    $other_language = ghalya_current_language() === 'ar' ? 'en' : 'ar';

    if (function_exists('pll_the_languages')) {
        $languages = pll_the_languages(array(
            'raw' => 1,
            'hide_if_empty' => 0,
            'hide_if_no_translation' => 0,
        ));

        if (is_array($languages)) {
            foreach ($languages as $language) {
                if (($language['slug'] ?? '') === $other_language && !empty($language['name'])) {
                    return (string) $language['name'];
                }
            }
        }
    }

    return $other_language === 'ar' ? 'العربية' : 'English';
}

/**
 * Add the original screen classes so the converted CSS remains unchanged.
 */
function ghalya_body_classes($classes)
{
    $screen = ghalya_current_screen();

    if ($screen === 'home') {
        $classes[] = 'mt-landing-page';
    } else {
        $classes[] = 'mt-application-page';
    }

    $step_numbers = array(
        'profile' => 1,
        'tier' => 2,
        'work' => 3,
        'proposal' => 4,
        'contact' => 5,
    );

    if (isset($step_numbers[$screen])) {
        $classes[] = 'mt-step-' . $step_numbers[$screen];
    }

    if ($screen === 'success') {
        $classes[] = 'mt-success-page';
    }

    return array_unique($classes);
}
add_filter('body_class', 'ghalya_body_classes');

/** Populate structured ACF fields once without overwriting later admin edits. */
function ghalya_seed_page_content($page_id, $screen, $language)
{
    $seed_key = '_ghalya_plain_content_seeded_' . $screen;

    if (get_post_meta($page_id, $seed_key, true) || !function_exists('update_field')) {
        return false;
    }

    $existing_content = function_exists('get_field') ? get_field('ghalya_' . $screen . '_content', $page_id) : false;

    if (is_array($existing_content) && array_filter($existing_content)) {
        update_post_meta($page_id, $seed_key, GHALYA_THEME_VERSION);
        return false;
    }

    $content = ghalya_default_content($screen, $language);

    if (!$content) {
        return false;
    }

    update_field('field_ghalya_' . $screen . '_content', $content, $page_id);
    update_post_meta($page_id, $seed_key, GHALYA_THEME_VERSION);

    return true;
}

/** Return the current page's structured content with a safe first-run fallback. */
function ghalya_screen_content($screen)
{
    $content = array();
    $page_id = get_queried_object_id();

    if (function_exists('get_field')) {
        $content = get_field('ghalya_' . $screen . '_content', $page_id);
    }

    return is_array($content) && $content ? $content : ghalya_default_content($screen, ghalya_current_language());
}

function ghalya_content_text($content, $key)
{
    return isset($content[$key]) && !is_array($content[$key]) ? (string) $content[$key] : '';
}

function ghalya_content_rows($content, $key)
{
    return isset($content[$key]) && is_array($content[$key]) ? $content[$key] : array();
}

function ghalya_asset_url($path)
{
    return GHALYA_THEME_URI . '/assets/' . ltrim($path, '/');
}

/** Render HTML from theme-owned template parts and plain ACF values. */
function ghalya_render_screen($screen)
{
    $allowed = array('home', 'profile', 'tier', 'work', 'proposal', 'contact', 'success', 'terms');

    if (!in_array($screen, $allowed, true)) {
        return;
    }

    $template = in_array($screen, array('profile', 'tier', 'work', 'proposal', 'contact'), true) ? 'application' : $screen;
    get_template_part('template-parts/screens/' . $template, null, array(
        'screen' => $screen,
        'content' => ghalya_screen_content($screen),
    ));
}
