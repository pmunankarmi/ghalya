<?php

if (!defined('ABSPATH')) {
    exit;
}

function ghalya_acf_page_locations()
{
    $locations = array();
    $page_ids = get_option('ghalya_page_ids', array());

    foreach (array('en', 'ar') as $language) {
        foreach (array('home', 'profile', 'tier', 'work', 'proposal', 'contact', 'success', 'terms') as $screen) {
            $page_id = isset($page_ids[$language][$screen]) ? absint($page_ids[$language][$screen]) : 0;

            if ($page_id) {
                $locations[] = array(array('param' => 'page', 'operator' => '==', 'value' => (string) $page_id));
            }
        }
    }

    // Template rules keep the field available before the page map is rebuilt.
    foreach (array('templates/home.php', 'templates/landing.php', 'templates/application.php', 'templates/success.php', 'templates/terms.php') as $template) {
        $locations[] = array(array('param' => 'page_template', 'operator' => '==', 'value' => $template));
    }

    return $locations;
}

function ghalya_register_acf_fields()
{
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_ghalya_page_content',
        'title' => __('Ghalya Page Content', 'ghalya'),
        'fields' => array(
            array(
                'key' => 'field_ghalya_page_markup',
                'label' => __('Page content', 'ghalya'),
                'name' => 'ghalya_page_markup',
                'type' => 'textarea',
                'instructions' => __('Edit the page sections and copy here. Keep the mt- classes and form field names intact so the supplied layout and application workflow continue to work.', 'ghalya'),
                'rows' => 36,
                'new_lines' => '',
            ),
        ),
        'location' => ghalya_acf_page_locations(),
        'position' => 'acf_after_title',
        'style' => 'seamless',
    ));

    acf_add_local_field_group(array(
        'key' => 'group_ghalya_submission_review',
        'title' => __('Submission review', 'ghalya'),
        'fields' => array(
            array(
                'key' => 'field_ghalya_status',
                'label' => __('Status', 'ghalya'),
                'name' => 'ghalya_status',
                'type' => 'select',
                'choices' => array('new' => __('New', 'ghalya'), 'reviewing' => __('Reviewing', 'ghalya'), 'approved' => __('Approved', 'ghalya'), 'declined' => __('Declined', 'ghalya')),
                'default_value' => 'new',
            ),
            array('key' => 'field_ghalya_reviewer_notes', 'label' => __('Reviewer notes', 'ghalya'), 'name' => 'ghalya_reviewer_notes', 'type' => 'textarea', 'rows' => 5),
        ),
        'location' => array(array(array('param' => 'post_type', 'operator' => '==', 'value' => 'ghalya_submission'))),
    ));
}
add_action('acf/init', 'ghalya_register_acf_fields');

function ghalya_register_acf_options()
{
    if (!function_exists('acf_add_options_sub_page')) {
        return;
    }

    acf_add_options_sub_page(array(
        'page_title' => __('Ghalya theme content', 'ghalya'),
        'menu_title' => __('Ghalya content', 'ghalya'),
        'parent_slug' => 'themes.php',
        'menu_slug' => 'ghalya-theme-content',
        'capability' => 'edit_theme_options',
    ));

    acf_add_options_sub_page(array(
        'page_title' => __('Ghalya email settings', 'ghalya'),
        'menu_title' => __('Email settings', 'ghalya'),
        'parent_slug' => 'edit.php?post_type=ghalya_submission',
        'menu_slug' => 'ghalya-email-settings',
        'capability' => 'manage_options',
    ));

    acf_add_local_field_group(array(
        'key' => 'group_ghalya_header_content',
        'title' => __('Header labels', 'ghalya'),
        'fields' => array(
            array('key' => 'field_ghalya_nav_benefits_en', 'label' => __('Benefits label — English', 'ghalya'), 'name' => 'ghalya_nav_benefits_en', 'type' => 'text'),
            array('key' => 'field_ghalya_nav_benefits_ar', 'label' => __('Benefits label — Arabic', 'ghalya'), 'name' => 'ghalya_nav_benefits_ar', 'type' => 'text'),
            array('key' => 'field_ghalya_nav_faq_en', 'label' => __('FAQ label — English', 'ghalya'), 'name' => 'ghalya_nav_faq_en', 'type' => 'text'),
            array('key' => 'field_ghalya_nav_faq_ar', 'label' => __('FAQ label — Arabic', 'ghalya'), 'name' => 'ghalya_nav_faq_ar', 'type' => 'text'),
            array('key' => 'field_ghalya_nav_terms_en', 'label' => __('Terms label — English', 'ghalya'), 'name' => 'ghalya_nav_terms_en', 'type' => 'text'),
            array('key' => 'field_ghalya_nav_terms_ar', 'label' => __('Terms label — Arabic', 'ghalya'), 'name' => 'ghalya_nav_terms_ar', 'type' => 'text'),
            array('key' => 'field_ghalya_language_switch_en', 'label' => __('Language switch — English page', 'ghalya'), 'name' => 'ghalya_language_switch_en', 'type' => 'text'),
            array('key' => 'field_ghalya_language_switch_ar', 'label' => __('Language switch — Arabic page', 'ghalya'), 'name' => 'ghalya_language_switch_ar', 'type' => 'text'),
        ),
        'location' => array(array(array('param' => 'options_page', 'operator' => '==', 'value' => 'ghalya-theme-content'))),
    ));

    acf_add_local_field_group(array(
        'key' => 'group_ghalya_email_settings',
        'title' => __('Submission email settings', 'ghalya'),
        'fields' => array(
            array('key' => 'field_ghalya_submission_email', 'label' => __('Recipient email', 'ghalya'), 'name' => 'ghalya_submission_email', 'type' => 'email', 'instructions' => __('Defaults to the WordPress administration email.', 'ghalya')),
            array('key' => 'field_ghalya_email_subject', 'label' => __('Email subject', 'ghalya'), 'name' => 'ghalya_email_subject', 'type' => 'text', 'default_value' => 'New Ghalya creator application'),
        ),
        'location' => array(array(array('param' => 'options_page', 'operator' => '==', 'value' => 'ghalya-email-settings'))),
    ));
}
add_action('acf/init', 'ghalya_register_acf_options');

/**
 * Seed global labels once, after which administrators fully control them.
 */
function ghalya_seed_acf_options()
{
    if (!function_exists('update_field') || get_option('ghalya_acf_options_seeded')) {
        return;
    }

    $defaults = array(
        'field_ghalya_nav_benefits_en' => 'Benefits',
        'field_ghalya_nav_benefits_ar' => 'المزايا',
        'field_ghalya_nav_faq_en' => 'FAQs',
        'field_ghalya_nav_faq_ar' => 'الأسئلة الشائعة',
        'field_ghalya_nav_terms_en' => 'Terms',
        'field_ghalya_nav_terms_ar' => 'الشروط',
        'field_ghalya_language_switch_en' => 'العربية',
        'field_ghalya_language_switch_ar' => 'English',
    );

    foreach ($defaults as $field_key => $value) {
        update_field($field_key, $value, 'option');
    }

    update_option('ghalya_acf_options_seeded', 1, false);
}
add_action('acf/init', 'ghalya_seed_acf_options', 20);
