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

/**
 * Read a translated label from the ACF theme options page.
 */
function ghalya_option_text($field_name)
{
    $language = ghalya_current_language();
    $translated_name = $field_name . '_' . $language;

    if (function_exists('get_field')) {
        return (string) get_field($translated_name, 'option');
    }

    return (string) get_option('options_' . $translated_name, '');
}

/**
 * Read the original supplied design once when seeding a page's ACF content.
 */
function ghalya_default_page_markup($screen, $language)
{
    $source_name = $screen === 'home' ? 'index' : $screen;
    $source_name .= $language === 'ar' ? '-ar.html' : '.html';
    $source_path = GHALYA_THEME_PATH . '/content-seed/' . $source_name;

    if (!is_readable($source_path)) {
        return '';
    }

    $source = file_get_contents($source_path);
    return preg_match('~<main\b[^>]*>.*?</main>~s', $source, $matches) ? $matches[0] : '';
}

/**
 * Populate a page's ACF field without overwriting later admin edits.
 */
function ghalya_seed_page_content($page_id, $screen, $language)
{
    if (get_post_meta($page_id, 'ghalya_page_markup', true) !== '') {
        return;
    }

    $markup = ghalya_default_page_markup($screen, $language);

    if ($markup === '') {
        return;
    }

    update_post_meta($page_id, 'ghalya_page_markup', $markup);
    update_post_meta($page_id, '_ghalya_page_markup', 'field_ghalya_page_markup');
}

/**
 * Render the matching static view through WordPress URLs and security fields.
 */
function ghalya_render_screen($screen)
{
    $language = ghalya_current_language();
    $page_id = get_queried_object_id();
    $content = '';

    if (function_exists('get_field')) {
        $content = (string) get_field('ghalya_page_markup', $page_id, false);
    }

    if ($content === '') {
        $content = (string) get_post_meta($page_id, 'ghalya_page_markup', true);
    }

    if ($content === '') {
        echo '<main class="mt-form-page"><div class="container"><p>';
        esc_html_e('Add this page’s content in the Ghalya Page Content fields.', 'ghalya');
        echo '</p></div></main>';
        return;
    }

    $asset_url = GHALYA_THEME_URI . '/assets/';
    $content = str_replace(
        array('src="assets/', 'href="assets/'),
        array('src="' . esc_url($asset_url), 'href="' . esc_url($asset_url)),
        $content
    );

    $screens = array('index' => 'home', 'profile' => 'profile', 'tier' => 'tier', 'work' => 'work', 'proposal' => 'proposal', 'contact' => 'contact', 'success' => 'success', 'terms' => 'terms');

    foreach ($screens as $file_name => $target_screen) {
        foreach (array('en', 'ar') as $target_language) {
            $static_file = $file_name . ($target_language === 'ar' ? '-ar' : '') . '.html';
            $target_url = esc_url(ghalya_page_url($target_screen, $target_language));

            $content = str_replace(
                array(
                    'href="' . $static_file . '"',
                    'action="' . $static_file . '"',
                    'data-mt-next="' . $static_file . '"',
                ),
                array(
                    'href="' . $target_url . '"',
                    'action="' . $target_url . '"',
                    'data-mt-next="' . $target_url . '"',
                ),
                $content
            );
        }
    }

    if ($screen === 'contact') {
        $content = str_replace(
            'action="sendmail.php"',
            'action="' . esc_url(admin_url('admin-post.php')) . '"',
            $content
        );

        $security_fields = '<input type="hidden" name="action" value="ghalya_submit_application">';
        $security_fields .= wp_nonce_field('ghalya_submit_application', '_ghalya_nonce', true, false);

        $content = preg_replace_callback(
            '~<form\b(?=[^>]*\bdata-mt-sendmail\b)[^>]*>~s',
            function ($form_match) use ($security_fields) {
                return $form_match[0] . $security_fields;
            },
            $content,
            1
        );

        if (!empty($_GET['submission_error'])) {
            $message = ghalya_submission_error_message(sanitize_key(wp_unslash($_GET['submission_error'])));
            $notice = '<div class="container"><div class="mt-form-card mt-submission-notice" role="alert">' . esc_html($message) . '</div></div>';
            $content = preg_replace('~(<main\b[^>]*>)~', '$1' . $notice, $content, 1);
        }
    }

    // Page markup is entered by trusted administrators through ACF.
    echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
