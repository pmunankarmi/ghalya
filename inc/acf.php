<?php

if (!defined('ABSPATH')) {
    exit;
}

/** Build a location rule for both language versions of a generated screen. */
function ghalya_acf_screen_locations($screen)
{
    $locations = array();
    $page_ids = get_option('ghalya_page_ids', array());

    foreach (array('en', 'ar') as $language) {
        $page_id = isset($page_ids[$language][$screen]) ? absint($page_ids[$language][$screen]) : 0;

        if ($page_id) {
            $locations[] = array(array('param' => 'page', 'operator' => '==', 'value' => (string) $page_id));
        }
    }

    return $locations;
}

function ghalya_acf_plain_field($scope, $name, $label, $type = 'text', $settings = array())
{
    return array_merge(array(
        'key' => 'field_ghalya_' . sanitize_key($scope . '_' . $name),
        'label' => $label,
        'name' => $name,
        'type' => $type,
    ), $settings);
}

function ghalya_acf_text_rows($scope, $name, $label, $sub_name = 'text', $sub_label = 'Text')
{
    return ghalya_acf_plain_field($scope, $name, $label, 'repeater', array(
        'layout' => 'table',
        'button_label' => __('Add item', 'ghalya'),
        'sub_fields' => array(
            ghalya_acf_plain_field($scope . '_' . $name, $sub_name, $sub_label),
        ),
    ));
}

function ghalya_acf_choice_rows($scope, $name, $label)
{
    return ghalya_acf_plain_field($scope, $name, $label, 'repeater', array(
        'layout' => 'table',
        'button_label' => __('Add choice', 'ghalya'),
        'sub_fields' => array(
            ghalya_acf_plain_field($scope . '_' . $name, 'value', __('Saved value', 'ghalya')),
            ghalya_acf_plain_field($scope . '_' . $name, 'label', __('Visible label', 'ghalya')),
        ),
    ));
}

function ghalya_acf_application_fields($screen)
{
    $fields = array(
        ghalya_acf_plain_field($screen, 'aside_title', __('Sidebar title', 'ghalya')),
        ghalya_acf_plain_field($screen, 'aside_intro', __('Sidebar introduction', 'ghalya'), 'textarea', array('rows' => 3, 'new_lines' => '')),
        ghalya_acf_text_rows($screen, 'progress_labels', __('Progress labels', 'ghalya'), 'label', __('Label', 'ghalya')),
        ghalya_acf_plain_field($screen, 'top_back_label', __('Top back-link label', 'ghalya')),
        ghalya_acf_plain_field($screen, 'step_label', __('Step label', 'ghalya')),
        ghalya_acf_plain_field($screen, 'title', __('Page title', 'ghalya')),
        ghalya_acf_plain_field($screen, 'intro', __('Page introduction', 'ghalya'), 'textarea', array('rows' => 3, 'new_lines' => '')),
    );

    if ($screen === 'profile') {
        $fields = array_merge($fields, array(
            ghalya_acf_plain_field($screen, 'full_name_label', __('Full-name label', 'ghalya')),
            ghalya_acf_plain_field($screen, 'full_name_placeholder', __('Full-name placeholder', 'ghalya')),
            ghalya_acf_plain_field($screen, 'instagram_label', __('Instagram label', 'ghalya')),
            ghalya_acf_plain_field($screen, 'instagram_placeholder', __('Instagram placeholder', 'ghalya')),
            ghalya_acf_plain_field($screen, 'followers_label', __('Followers label', 'ghalya')),
            ghalya_acf_choice_rows($screen, 'followers_choices', __('Follower choices', 'ghalya')),
            ghalya_acf_plain_field($screen, 'city_label', __('City label', 'ghalya')),
            ghalya_acf_plain_field($screen, 'city_placeholder', __('City placeholder', 'ghalya')),
            ghalya_acf_choice_rows($screen, 'city_choices', __('City choices', 'ghalya')),
            ghalya_acf_plain_field($screen, 'category_label', __('Content-category label', 'ghalya')),
            ghalya_acf_choice_rows($screen, 'category_choices', __('Content-category choices', 'ghalya')),
            ghalya_acf_plain_field($screen, 'back_button', __('Cancel button', 'ghalya')),
            ghalya_acf_plain_field($screen, 'next_button', __('Next button', 'ghalya')),
        ));
    }

    if ($screen === 'tier') {
        $fields = array_merge($fields, array(
            ghalya_acf_plain_field($screen, 'tier_name', __('Tier name', 'ghalya')),
            ghalya_acf_plain_field($screen, 'tier_amount', __('Reward amount', 'ghalya')),
            ghalya_acf_plain_field($screen, 'tier_suffix', __('Reward description', 'ghalya')),
            ghalya_acf_plain_field($screen, 'deliverables_label', __('Deliverables heading', 'ghalya')),
            ghalya_acf_text_rows($screen, 'deliverables', __('Deliverables', 'ghalya')),
            ghalya_acf_plain_field($screen, 'note', __('Tier note', 'ghalya'), 'textarea', array('rows' => 4, 'new_lines' => '')),
            ghalya_acf_plain_field($screen, 'back_button', __('Back button', 'ghalya')),
            ghalya_acf_plain_field($screen, 'next_button', __('Continue button', 'ghalya')),
        ));
    }

    if ($screen === 'work') {
        foreach (array('instagram' => 'Instagram', 'tiktok' => 'TikTok', 'snapchat' => 'Snapchat', 'brand_content' => 'Previous branded content') as $name => $label) {
            $fields[] = ghalya_acf_plain_field($screen, $name . '_label', sprintf(__('%s field label', 'ghalya'), $label));
            $fields[] = ghalya_acf_plain_field($screen, $name . '_placeholder', sprintf(__('%s placeholder', 'ghalya'), $label));
        }

        $fields = array_merge($fields, array(
            ghalya_acf_plain_field($screen, 'add_link_button', __('Add-link button', 'ghalya')),
            ghalya_acf_plain_field($screen, 'back_button', __('Back button', 'ghalya')),
            ghalya_acf_plain_field($screen, 'next_button', __('Continue button', 'ghalya')),
        ));
    }

    if ($screen === 'proposal') {
        $fields = array_merge($fields, array(
            ghalya_acf_plain_field($screen, 'brands_label', __('Brands label', 'ghalya')),
            ghalya_acf_choice_rows($screen, 'brand_choices', __('Brand choices', 'ghalya')),
            ghalya_acf_plain_field($screen, 'availability_label', __('Availability label', 'ghalya')),
            ghalya_acf_plain_field($screen, 'back_button', __('Back button', 'ghalya')),
            ghalya_acf_plain_field($screen, 'next_button', __('Continue button', 'ghalya')),
        ));
    }

    if ($screen === 'contact') {
        $fields = array_merge($fields, array(
            ghalya_acf_plain_field($screen, 'email_label', __('Email label', 'ghalya')),
            ghalya_acf_plain_field($screen, 'email_placeholder', __('Email placeholder', 'ghalya')),
            ghalya_acf_plain_field($screen, 'phone_label', __('Phone label', 'ghalya')),
            ghalya_acf_plain_field($screen, 'phone_placeholder', __('Phone placeholder', 'ghalya')),
            ghalya_acf_plain_field($screen, 'consent_before', __('Consent text before link', 'ghalya')),
            ghalya_acf_plain_field($screen, 'consent_link', __('Terms link label', 'ghalya')),
            ghalya_acf_plain_field($screen, 'consent_after', __('Consent text after link', 'ghalya')),
            ghalya_acf_plain_field($screen, 'back_button', __('Back button', 'ghalya')),
            ghalya_acf_plain_field($screen, 'submit_button', __('Submit button', 'ghalya')),
        ));
    }

    return $fields;
}

function ghalya_acf_home_fields()
{
    $fields = array();
    $text_fields = array(
        'hero_title' => 'Hero title', 'hero_accent' => 'Hero highlighted text', 'hero_eyebrow' => 'Hero eyebrow',
        'creators_alt' => 'Creator image description', 'earn_note' => 'Earning note', 'reward_title' => 'Reward title',
        'reward_amount' => 'Reward amount', 'reward_suffix' => 'Mobile reward suffix', 'reward_desktop_suffix' => 'Desktop reward suffix',
        'reward_detail' => 'Reward details', 'join_button' => 'Join button', 'explore_button' => 'Explore button',
        'tiers_label' => 'Tier-list description', 'partners_eyebrow' => 'Partners eyebrow', 'partners_title' => 'Partners title',
        'partners_mobile_prefix' => 'Mobile universe prefix', 'partners_mobile_suffix' => 'Mobile universe suffix',
        'partners_mobile_copy' => 'Mobile partners copy', 'partners_label' => 'Partner carousel description',
        'benefits_eyebrow' => 'Benefits eyebrow', 'benefits_title' => 'Benefits title', 'deliver_eyebrow' => 'Deliverables eyebrow',
        'deliver_title' => 'Deliverables title', 'looking_eyebrow' => 'Eligibility eyebrow', 'looking_title' => 'Eligibility title',
        'categories_title' => 'Categories title', 'start_button' => 'Start button', 'faq_eyebrow' => 'FAQ eyebrow', 'faq_title' => 'FAQ title',
    );

    foreach ($text_fields as $name => $label) {
        $fields[] = ghalya_acf_plain_field('home', $name, __($label, 'ghalya'));
    }

    foreach (array('hero_intro' => 'Hero introduction', 'hero_intro_emphasis' => 'Hero emphasized copy', 'benefits_intro' => 'Benefits introduction', 'faq_intro' => 'FAQ introduction') as $name => $label) {
        $fields[] = ghalya_acf_plain_field('home', $name, __($label, 'ghalya'), 'textarea', array('rows' => 3, 'new_lines' => ''));
    }

    $fields[] = ghalya_acf_plain_field('home', 'tiers', __('Creator tiers', 'ghalya'), 'repeater', array(
        'layout' => 'table',
        'sub_fields' => array(
            ghalya_acf_plain_field('home_tier_rows', 'label', __('Label', 'ghalya')),
            ghalya_acf_plain_field('home_tier_rows', 'active', __('Active', 'ghalya'), 'true_false', array('ui' => 1)),
        ),
    ));

    foreach (array('benefits' => 'Benefits', 'deliverables' => 'Deliverables') as $name => $label) {
        $fields[] = ghalya_acf_plain_field('home', $name, __($label, 'ghalya'), 'repeater', array(
            'layout' => 'block',
            'sub_fields' => array(
                ghalya_acf_plain_field('home_' . rtrim($name, 's') . '_rows', 'title', __('Title', 'ghalya')),
                ghalya_acf_plain_field('home_' . rtrim($name, 's') . '_rows', 'description', __('Description', 'ghalya'), 'textarea', array('rows' => 2, 'new_lines' => '')),
            ),
        ));
    }

    $fields[] = ghalya_acf_text_rows('home', 'requirements', __('Eligibility requirements', 'ghalya'));
    $fields[] = ghalya_acf_text_rows('home', 'categories', __('Creator categories', 'ghalya'));
    $fields[] = ghalya_acf_plain_field('home', 'faqs', __('FAQs', 'ghalya'), 'repeater', array(
        'layout' => 'block',
        'button_label' => __('Add FAQ', 'ghalya'),
        'sub_fields' => array(
            ghalya_acf_plain_field('home_faqs', 'question', __('Question', 'ghalya')),
            ghalya_acf_plain_field('home_faqs', 'answer', __('Answer', 'ghalya'), 'textarea', array('rows' => 3, 'new_lines' => '')),
        ),
    ));

    return $fields;
}

function ghalya_register_acf_fields()
{
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    $screen_fields = array(
        'home' => ghalya_acf_home_fields(),
        'profile' => ghalya_acf_application_fields('profile'),
        'tier' => ghalya_acf_application_fields('tier'),
        'work' => ghalya_acf_application_fields('work'),
        'proposal' => ghalya_acf_application_fields('proposal'),
        'contact' => ghalya_acf_application_fields('contact'),
        'success' => array(
            ghalya_acf_plain_field('success', 'eyebrow', __('Eyebrow', 'ghalya')),
            ghalya_acf_plain_field('success', 'title', __('Title', 'ghalya')),
            ghalya_acf_plain_field('success', 'message', __('Message', 'ghalya'), 'textarea', array('rows' => 4, 'new_lines' => '')),
            ghalya_acf_plain_field('success', 'social_handle', __('Social handle', 'ghalya')),
            ghalya_acf_plain_field('success', 'back_button', __('Back button', 'ghalya')),
        ),
        'terms' => array(
            ghalya_acf_plain_field('terms', 'eyebrow', __('Eyebrow', 'ghalya')),
            ghalya_acf_plain_field('terms', 'title', __('Title', 'ghalya')),
            ghalya_acf_plain_field('terms', 'intro', __('Introduction', 'ghalya'), 'textarea', array('rows' => 3, 'new_lines' => '')),
            ghalya_acf_plain_field('terms', 'sections', __('Terms sections', 'ghalya'), 'repeater', array(
                'layout' => 'block',
                'button_label' => __('Add section', 'ghalya'),
                'sub_fields' => array(
                    ghalya_acf_plain_field('terms_sections', 'title', __('Heading', 'ghalya')),
                    ghalya_acf_plain_field('terms_sections', 'body', __('Body', 'ghalya'), 'textarea', array('rows' => 6, 'new_lines' => '')),
                ),
            )),
            ghalya_acf_plain_field('terms', 'start_button', __('Start button', 'ghalya')),
            ghalya_acf_plain_field('terms', 'back_button', __('Back button', 'ghalya')),
        ),
    );

    foreach ($screen_fields as $screen => $fields) {
        acf_add_local_field_group(array(
            'key' => 'group_ghalya_' . $screen . '_content',
            'title' => sprintf(__('Ghalya %s content', 'ghalya'), ucfirst($screen)),
            'fields' => array(array(
                'key' => 'field_ghalya_' . $screen . '_content',
                'label' => __('Page content', 'ghalya'),
                'name' => 'ghalya_' . $screen . '_content',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => $fields,
            )),
            'location' => ghalya_acf_screen_locations($screen),
            'position' => 'acf_after_title',
            'style' => 'seamless',
        ));
    }

    acf_add_local_field_group(array(
        'key' => 'group_ghalya_submission_review',
        'title' => __('Submission review', 'ghalya'),
        'fields' => array(
            array(
                'key' => 'field_ghalya_status', 'label' => __('Status', 'ghalya'), 'name' => 'ghalya_status', 'type' => 'select',
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
        'page_title' => __('Ghalya notification settings', 'ghalya'), 'menu_title' => __('Notifications', 'ghalya'),
        'parent_slug' => 'edit.php?post_type=ghalya_submission', 'menu_slug' => 'ghalya-email-settings', 'capability' => 'manage_options',
    ));

    acf_add_local_field_group(array(
        'key' => 'group_ghalya_email_settings', 'title' => __('Form submission notifications', 'ghalya'),
        'fields' => array(
            array('key' => 'field_ghalya_admin_notification_enabled', 'label' => __('Send administrator notification', 'ghalya'), 'name' => 'ghalya_admin_notification_enabled', 'type' => 'true_false', 'ui' => 1, 'default_value' => 1),
            array('key' => 'field_ghalya_submission_email', 'label' => __('Recipient email', 'ghalya'), 'name' => 'ghalya_submission_email', 'type' => 'email', 'instructions' => __('Defaults to the WordPress administration email.', 'ghalya')),
            array('key' => 'field_ghalya_email_subject', 'label' => __('Administrator email subject', 'ghalya'), 'name' => 'ghalya_email_subject', 'type' => 'text', 'instructions' => __('Available tokens: {name}, {email}, {submission_id}.', 'ghalya')),
            array('key' => 'field_ghalya_email_heading', 'label' => __('Administrator email heading', 'ghalya'), 'name' => 'ghalya_email_heading', 'type' => 'text'),
            array('key' => 'field_ghalya_email_intro', 'label' => __('Administrator email introduction', 'ghalya'), 'name' => 'ghalya_email_intro', 'type' => 'textarea', 'rows' => 3, 'new_lines' => ''),
            array('key' => 'field_ghalya_email_review_button', 'label' => __('Review button label', 'ghalya'), 'name' => 'ghalya_email_review_button', 'type' => 'text'),
            array('key' => 'field_ghalya_applicant_notification_enabled', 'label' => __('Send applicant confirmation', 'ghalya'), 'name' => 'ghalya_applicant_notification_enabled', 'type' => 'true_false', 'ui' => 1, 'default_value' => 1),
            array('key' => 'field_ghalya_applicant_email_subject_en', 'label' => __('Applicant subject — English', 'ghalya'), 'name' => 'ghalya_applicant_email_subject_en', 'type' => 'text', 'instructions' => __('Available tokens: {name}, {email}, {submission_id}.', 'ghalya')),
            array('key' => 'field_ghalya_applicant_email_subject_ar', 'label' => __('Applicant subject — Arabic', 'ghalya'), 'name' => 'ghalya_applicant_email_subject_ar', 'type' => 'text', 'instructions' => __('Available tokens: {name}, {email}, {submission_id}.', 'ghalya')),
            array('key' => 'field_ghalya_applicant_email_heading_en', 'label' => __('Applicant heading — English', 'ghalya'), 'name' => 'ghalya_applicant_email_heading_en', 'type' => 'text'),
            array('key' => 'field_ghalya_applicant_email_heading_ar', 'label' => __('Applicant heading — Arabic', 'ghalya'), 'name' => 'ghalya_applicant_email_heading_ar', 'type' => 'text'),
            array('key' => 'field_ghalya_applicant_email_message_en', 'label' => __('Applicant message — English', 'ghalya'), 'name' => 'ghalya_applicant_email_message_en', 'type' => 'textarea', 'rows' => 4, 'new_lines' => ''),
            array('key' => 'field_ghalya_applicant_email_message_ar', 'label' => __('Applicant message — Arabic', 'ghalya'), 'name' => 'ghalya_applicant_email_message_ar', 'type' => 'textarea', 'rows' => 4, 'new_lines' => ''),
            array('key' => 'field_ghalya_email_footer_en', 'label' => __('Email footer — English', 'ghalya'), 'name' => 'ghalya_email_footer_en', 'type' => 'textarea', 'rows' => 2, 'new_lines' => ''),
            array('key' => 'field_ghalya_email_footer_ar', 'label' => __('Email footer — Arabic', 'ghalya'), 'name' => 'ghalya_email_footer_ar', 'type' => 'textarea', 'rows' => 2, 'new_lines' => ''),
            array('key' => 'field_ghalya_email_from_name', 'label' => __('Sender name', 'ghalya'), 'name' => 'ghalya_email_from_name', 'type' => 'text'),
            array('key' => 'field_ghalya_email_from_address', 'label' => __('Sender email', 'ghalya'), 'name' => 'ghalya_email_from_address', 'type' => 'email', 'instructions' => __('Leave blank to use the WordPress mail sender.', 'ghalya')),
        ),
        'location' => array(array(array('param' => 'options_page', 'operator' => '==', 'value' => 'ghalya-email-settings'))),
    ));
}
add_action('acf/init', 'ghalya_register_acf_options');

/** Seed notification copy without overwriting administrator edits. */
function ghalya_seed_notification_options()
{
    if (!function_exists('update_field')) {
        return;
    }

    $defaults = array(
        'field_ghalya_admin_notification_enabled' => array('ghalya_admin_notification_enabled', 1),
        'field_ghalya_email_subject' => array('ghalya_email_subject', 'New Ghalya creator application — {name}'),
        'field_ghalya_email_heading' => array('ghalya_email_heading', 'A new creator application has arrived'),
        'field_ghalya_email_intro' => array('ghalya_email_intro', 'Review the applicant details below and follow up from the Ghalya Submissions area.'),
        'field_ghalya_email_review_button' => array('ghalya_email_review_button', 'Review application'),
        'field_ghalya_applicant_notification_enabled' => array('ghalya_applicant_notification_enabled', 1),
        'field_ghalya_applicant_email_subject_en' => array('ghalya_applicant_email_subject_en', 'We received your Ghalya application'),
        'field_ghalya_applicant_email_subject_ar' => array('ghalya_applicant_email_subject_ar', 'تم استلام طلب الانضمام إلى غالية'),
        'field_ghalya_applicant_email_heading_en' => array('ghalya_applicant_email_heading_en', 'Thank you for applying'),
        'field_ghalya_applicant_email_heading_ar' => array('ghalya_applicant_email_heading_ar', 'شكرًا لتقديم طلبك'),
        'field_ghalya_applicant_email_message_en' => array('ghalya_applicant_email_message_en', 'Your application to join the Ghalya creator community has been received. Our team will review your details and contact you within 14 days.'),
        'field_ghalya_applicant_email_message_ar' => array('ghalya_applicant_email_message_ar', 'تم استلام طلبك للانضمام إلى مجتمع غالية لصنّاع المحتوى. سيراجع فريقنا بياناتك ويتواصل معك خلال 14 يومًا.'),
        'field_ghalya_email_footer_en' => array('ghalya_email_footer_en', 'Ghalya Creator Program'),
        'field_ghalya_email_footer_ar' => array('ghalya_email_footer_ar', 'برنامج غالية لصنّاع المحتوى'),
        'field_ghalya_email_from_name' => array('ghalya_email_from_name', 'Ghalya Creator Program'),
    );

    foreach ($defaults as $field_key => $field) {
        if (get_option('options_' . $field[0], null) === null) {
            update_field($field_key, $field[1], 'option');
        }
    }
}
add_action('acf/init', 'ghalya_seed_notification_options', 20);
