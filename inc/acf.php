<?php

if (!defined('ABSPATH')) {
    exit;
}

function ghalya_register_acf_fields()
{
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_ghalya_landing',
        'title' => __('Ghalya landing content', 'ghalya'),
        'fields' => array(
            array('key' => 'field_ghalya_hero_title', 'label' => __('Hero title', 'ghalya'), 'name' => 'ghalya_hero_title', 'type' => 'text'),
            array('key' => 'field_ghalya_hero_accent', 'label' => __('Highlighted word', 'ghalya'), 'name' => 'ghalya_hero_accent', 'type' => 'text'),
            array('key' => 'field_ghalya_hero_copy', 'label' => __('Hero description', 'ghalya'), 'name' => 'ghalya_hero_copy', 'type' => 'textarea', 'rows' => 3),
            array('key' => 'field_ghalya_join_label', 'label' => __('Join button label', 'ghalya'), 'name' => 'ghalya_join_label', 'type' => 'text'),
        ),
        'location' => array(array(array('param' => 'page_template', 'operator' => '==', 'value' => 'templates/landing.php'))),
        'position' => 'acf_after_title',
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
        'page_title' => __('Ghalya email settings', 'ghalya'),
        'menu_title' => __('Email settings', 'ghalya'),
        'parent_slug' => 'edit.php?post_type=ghalya_submission',
        'menu_slug' => 'ghalya-email-settings',
        'capability' => 'manage_options',
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

