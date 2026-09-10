<?php

if (!defined('ABSPATH')) {
    exit;
}

function ghalya_theme_setup()
{
    load_theme_textdomain('ghalya', GHALYA_THEME_PATH . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'gallery', 'caption', 'style', 'script'));
}
add_action('after_setup_theme', 'ghalya_theme_setup');

function ghalya_enqueue_assets()
{
    $is_arabic = ghalya_current_language() === 'ar';
    $bootstrap_file = $is_arabic ? 'bootstrap.rtl.min.css' : 'bootstrap.min.css';

    wp_enqueue_style('ghalya-bootstrap', GHALYA_THEME_URI . '/assets/vendor/' . $bootstrap_file, array(), '5.3.3');
    wp_enqueue_style('ghalya-aos', GHALYA_THEME_URI . '/assets/vendor/aos.css', array(), '2.3.4');
    wp_enqueue_style('ghalya-swiper', GHALYA_THEME_URI . '/assets/vendor/swiper-bundle.min.css', array(), '12.0.3');
    wp_enqueue_style('ghalya-theme', GHALYA_THEME_URI . '/assets/css/mt-style.css', array('ghalya-bootstrap'), filemtime(GHALYA_THEME_PATH . '/assets/css/mt-style.css'));

    wp_enqueue_script('ghalya-bootstrap', GHALYA_THEME_URI . '/assets/vendor/bootstrap.bundle.min.js', array(), '5.3.3', true);
    wp_enqueue_script('ghalya-aos', GHALYA_THEME_URI . '/assets/vendor/aos.js', array(), '2.3.4', true);
    wp_enqueue_script('ghalya-swiper', GHALYA_THEME_URI . '/assets/vendor/swiper-bundle.min.js', array(), '12.0.3', true);
    wp_enqueue_script('ghalya-validation', GHALYA_THEME_URI . '/assets/vendor/jquery.validate.js', array('jquery'), '1.21.0', true);
    wp_enqueue_script('ghalya-app', GHALYA_THEME_URI . '/assets/js/mt-app.js', array('jquery', 'ghalya-validation', 'ghalya-aos', 'ghalya-swiper'), filemtime(GHALYA_THEME_PATH . '/assets/js/mt-app.js'), true);
}
add_action('wp_enqueue_scripts', 'ghalya_enqueue_assets');

/**
 * Create the connected English and Arabic pages when the theme is activated.
 */
function ghalya_create_required_pages()
{
    $definitions = array(
        'home' => array('template' => 'templates/landing.php', 'en' => array('Ghalya Creator Program', 'ghalya'), 'ar' => array('برنامج صناع المحتوى من غالية', 'ghalya-ar')),
        'profile' => array('template' => 'templates/application.php', 'en' => array('Your Profile', 'profile'), 'ar' => array('ملفك الشخصي', 'profile-ar')),
        'tier' => array('template' => 'templates/application.php', 'en' => array('Your Tier', 'tier'), 'ar' => array('فئتك', 'tier-ar')),
        'work' => array('template' => 'templates/application.php', 'en' => array('Your Work', 'work'), 'ar' => array('أعمالك', 'work-ar')),
        'proposal' => array('template' => 'templates/application.php', 'en' => array('Your Proposal', 'proposal'), 'ar' => array('مقترحك', 'proposal-ar')),
        'contact' => array('template' => 'templates/application.php', 'en' => array('Contact Details', 'contact'), 'ar' => array('تفاصيل التواصل', 'contact-ar')),
        'success' => array('template' => 'templates/success.php', 'en' => array('Application Submitted', 'success'), 'ar' => array('تم تقديم الطلب', 'success-ar')),
        'terms' => array('template' => 'templates/terms.php', 'en' => array('Terms and Conditions', 'terms'), 'ar' => array('الشروط والأحكام', 'terms-ar')),
    );

    $page_ids = get_option('ghalya_page_ids', array());

    foreach ($definitions as $screen => $definition) {
        foreach (array('en', 'ar') as $language) {
            $existing_id = isset($page_ids[$language][$screen]) ? absint($page_ids[$language][$screen]) : 0;

            if (!$existing_id || !get_post_status($existing_id)) {
                $existing_page = get_page_by_path($definition[$language][1]);
                $is_theme_page = $existing_page
                    && get_post_meta($existing_page->ID, '_ghalya_screen', true) === $screen
                    && get_post_meta($existing_page->ID, '_ghalya_language', true) === $language;
                $existing_id = $is_theme_page ? $existing_page->ID : 0;
            }

            if (!$existing_id) {
                $existing_id = wp_insert_post(array(
                    'post_title' => $definition[$language][0],
                    'post_name' => $definition[$language][1],
                    'post_status' => 'publish',
                    'post_type' => 'page',
                ));
            }

            if (!is_wp_error($existing_id) && $existing_id) {
                update_post_meta($existing_id, '_wp_page_template', $definition['template']);
                update_post_meta($existing_id, '_ghalya_screen', $screen);
                update_post_meta($existing_id, '_ghalya_language', $language);
                $page_ids[$language][$screen] = (int) $existing_id;

                if (function_exists('pll_set_post_language')) {
                    pll_set_post_language($existing_id, $language);
                }
            }
        }
    }

    update_option('ghalya_page_ids', $page_ids, false);
    ghalya_link_polylang_pages();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'ghalya_create_required_pages');

function ghalya_link_polylang_pages()
{
    if (!function_exists('pll_save_post_translations')) {
        return;
    }

    $page_ids = get_option('ghalya_page_ids', array());

    foreach (array('home', 'profile', 'tier', 'work', 'proposal', 'contact', 'success', 'terms') as $screen) {
        $english_id = isset($page_ids['en'][$screen]) ? absint($page_ids['en'][$screen]) : 0;
        $arabic_id = isset($page_ids['ar'][$screen]) ? absint($page_ids['ar'][$screen]) : 0;

        if ($english_id && $arabic_id) {
            if (function_exists('pll_set_post_language')) {
                pll_set_post_language($english_id, 'en');
                pll_set_post_language($arabic_id, 'ar');
            }

            pll_save_post_translations(array('en' => $english_id, 'ar' => $arabic_id));
        }
    }
}
add_action('admin_init', 'ghalya_link_polylang_pages');

function ghalya_dependency_notice()
{
    if (!current_user_can('activate_plugins')) {
        return;
    }

    $missing = array();

    if (!function_exists('acf_add_options_sub_page')) {
        $missing[] = 'ACF Pro';
    }

    if (!function_exists('pll_current_language')) {
        $missing[] = 'Polylang';
    }

    if (!$missing) {
        return;
    }

    echo '<div class="notice notice-warning"><p>';
    echo esc_html(sprintf(__('Ghalya Theme: activate %s to enable editable fields and bilingual page management.', 'ghalya'), implode(' and ', $missing)));
    echo '</p></div>';
}
add_action('admin_notices', 'ghalya_dependency_notice');
