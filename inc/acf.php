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
        ));
    }

    if ($screen === 'tier') {
        $fields = array_merge($fields, array(
            ghalya_acf_plain_field($screen, 'tier_name', __('Tier name', 'ghalya')),
            ghalya_acf_plain_field($screen, 'tier_amount', __('Reward amount', 'ghalya')),
            ghalya_acf_plain_field($screen, 'creator_label', __('Creator label', 'ghalya'), 'text', array(
                'instructions' => __('Used with the selected homepage tier label, for example “Micro Creator”.', 'ghalya'),
            )),
            ghalya_acf_plain_field($screen, 'tier_suffix', __('Reward description', 'ghalya')),
            ghalya_acf_plain_field($screen, 'deliverables_label', __('Deliverables heading', 'ghalya')),
            ghalya_acf_text_rows($screen, 'deliverables', __('Deliverables', 'ghalya')),
            ghalya_acf_plain_field($screen, 'note', __('Tier note', 'ghalya'), 'textarea', array('rows' => 4, 'new_lines' => '')),
        ));
    }

    if ($screen === 'work') {
        foreach (array('instagram' => 'Instagram', 'tiktok' => 'TikTok', 'snapchat' => 'Snapchat', 'brand_content' => 'Previous branded content') as $name => $label) {
            $fields[] = ghalya_acf_plain_field($screen, $name . '_label', sprintf(__('%s field label', 'ghalya'), $label));
            $fields[] = ghalya_acf_plain_field($screen, $name . '_placeholder', sprintf(__('%s placeholder', 'ghalya'), $label));
        }

        $fields = array_merge($fields, array(
            ghalya_acf_plain_field($screen, 'add_link_button', __('Add-link button', 'ghalya')),
        ));
    }

    if ($screen === 'proposal') {
        $fields = array_merge($fields, array(
            ghalya_acf_plain_field($screen, 'brands_label', __('Brands label', 'ghalya')),
            ghalya_acf_choice_rows($screen, 'brand_choices', __('Brand choices', 'ghalya')),
            ghalya_acf_plain_field($screen, 'availability_label', __('Availability label', 'ghalya')),
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
        ));
    }

    return $fields;
}

function ghalya_acf_section_tab($name, $label)
{
    return array(
        'key' => 'field_ghalya_home_tab_' . sanitize_key($name),
        'label' => $label,
        'type' => 'tab',
        'placement' => 'top',
    );
}

function ghalya_acf_home_fields()
{
    $tier_field = ghalya_acf_plain_field('home', 'tiers', __('Creator tiers', 'ghalya'), 'repeater', array(
        'layout' => 'block',
        'button_label' => __('Add tier', 'ghalya'),
        'sub_fields' => array(
            ghalya_acf_plain_field('home_tier_rows', 'label', __('Label', 'ghalya')),
            ghalya_acf_plain_field('home_tier_rows', 'active', __('Active', 'ghalya'), 'true_false', array('ui' => 1)),
            ghalya_acf_plain_field('home_tier_rows', 'reward_amount', __('Reward amount', 'ghalya')),
        ),
    ));

    $partner_logos_field = ghalya_acf_plain_field('home', 'partner_logos', __('Partner logos', 'ghalya'), 'repeater', array(
        'layout' => 'table',
        'button_label' => __('Add partner logo', 'ghalya'),
        'sub_fields' => array(
            ghalya_acf_plain_field('home_partner_logo_rows', 'logo', __('Logo', 'ghalya'), 'image', array(
                'return_format' => 'id',
                'preview_size' => 'thumbnail',
                'library' => 'all',
            )),
            ghalya_acf_plain_field('home_partner_logo_rows', 'name', __('Partner name', 'ghalya')),
        ),
    ));

    $benefits_field = ghalya_acf_plain_field('home', 'benefits', __('Benefits', 'ghalya'), 'repeater', array(
        'layout' => 'block',
        'sub_fields' => array(
            ghalya_acf_plain_field('home_benefit_rows', 'icon', __('Icon', 'ghalya'), 'image', array('return_format' => 'id', 'preview_size' => 'thumbnail')),
            ghalya_acf_plain_field('home_benefit_rows', 'title', __('Title', 'ghalya')),
            ghalya_acf_plain_field('home_benefit_rows', 'description', __('Description', 'ghalya'), 'textarea', array('rows' => 2, 'new_lines' => '')),
        ),
    ));

    $deliverables_field = ghalya_acf_plain_field('home', 'deliverables', __('Deliverables', 'ghalya'), 'repeater', array(
        'layout' => 'block',
        'sub_fields' => array(
            ghalya_acf_plain_field('home_deliverable_rows', 'icon', __('Icon', 'ghalya'), 'image', array('return_format' => 'id', 'preview_size' => 'thumbnail')),
            ghalya_acf_plain_field('home_deliverable_rows', 'title', __('Title', 'ghalya')),
            ghalya_acf_plain_field('home_deliverable_rows', 'description', __('Description', 'ghalya'), 'textarea', array('rows' => 2, 'new_lines' => '')),
        ),
    ));

    $faqs_field = ghalya_acf_plain_field('home', 'faqs', __('FAQs', 'ghalya'), 'repeater', array(
        'layout' => 'block',
        'button_label' => __('Add FAQ', 'ghalya'),
        'sub_fields' => array(
            ghalya_acf_plain_field('home_faqs', 'question', __('Question', 'ghalya')),
            ghalya_acf_plain_field('home_faqs', 'answer', __('Answer', 'ghalya'), 'textarea', array('rows' => 3, 'new_lines' => '')),
            ghalya_acf_plain_field('home_faqs', 'terms_link_label', __('Terms-page link label', 'ghalya'), 'text', array(
                'instructions' => __('Optional. When entered, this text links to the translated Terms page after the answer.', 'ghalya'),
            )),
        ),
    ));

    return array(
        ghalya_acf_section_tab('hero_rewards', __('Hero & Rewards', 'ghalya')),
        ghalya_acf_plain_field('home', 'hero_eyebrow', __('Hero eyebrow', 'ghalya')),
        ghalya_acf_plain_field('home', 'hero_title', __('Hero title', 'ghalya')),
        ghalya_acf_plain_field('home', 'hero_accent', __('Hero highlighted text', 'ghalya')),
        ghalya_acf_plain_field('home', 'hero_intro', __('Hero introduction', 'ghalya'), 'textarea', array('rows' => 3, 'new_lines' => '')),
        ghalya_acf_plain_field('home', 'hero_intro_emphasis', __('Hero emphasized copy', 'ghalya'), 'textarea', array('rows' => 3, 'new_lines' => '')),
        ghalya_acf_plain_field('home', 'creators_alt', __('Creator image description', 'ghalya')),
        ghalya_acf_plain_field('home', 'earn_note', __('Earning note', 'ghalya')),
        ghalya_acf_plain_field('home', 'reward_title', __('Reward title', 'ghalya')),
        ghalya_acf_plain_field('home', 'reward_amount', __('Default reward amount', 'ghalya')),
        ghalya_acf_plain_field('home', 'reward_suffix', __('Default mobile reward suffix', 'ghalya')),
        ghalya_acf_plain_field('home', 'reward_desktop_suffix', __('Default desktop reward suffix', 'ghalya')),
        ghalya_acf_plain_field('home', 'reward_detail', __('Default reward details', 'ghalya')),
        ghalya_acf_plain_field('home', 'tiers_label', __('Tier-list description', 'ghalya')),
        $tier_field,
        ghalya_acf_plain_field('home', 'join_button', __('Join button', 'ghalya')),

        ghalya_acf_section_tab('partners', __('Partners', 'ghalya')),
        ghalya_acf_plain_field('home', 'partners_eyebrow', __('Partners eyebrow', 'ghalya')),
        ghalya_acf_plain_field('home', 'partners_title', __('Partners title', 'ghalya')),
        ghalya_acf_plain_field('home', 'partners_mobile_prefix', __('Mobile universe prefix', 'ghalya')),
        ghalya_acf_plain_field('home', 'partners_mobile_suffix', __('Mobile universe suffix', 'ghalya')),
        ghalya_acf_plain_field('home', 'partners_mobile_copy', __('Mobile partners copy', 'ghalya')),
        ghalya_acf_plain_field('home', 'partners_label', __('Partner carousel description', 'ghalya')),
        $partner_logos_field,

        ghalya_acf_section_tab('benefits', __('Benefits', 'ghalya')),
        ghalya_acf_plain_field('home', 'benefits_eyebrow', __('Benefits eyebrow', 'ghalya')),
        ghalya_acf_plain_field('home', 'benefits_title', __('Benefits title', 'ghalya')),
        ghalya_acf_plain_field('home', 'benefits_intro', __('Benefits introduction', 'ghalya'), 'textarea', array('rows' => 3, 'new_lines' => '')),
        $benefits_field,

        ghalya_acf_section_tab('impact', __('Impact', 'ghalya')),
        ghalya_acf_plain_field('home', 'impact_title', __('Impact title', 'ghalya')),
        ghalya_acf_plain_field('home', 'impact_stats', __('Impact statistics', 'ghalya'), 'repeater', array(
            'layout' => 'table',
            'sub_fields' => array(
                ghalya_acf_plain_field('home_impact', 'value', __('Value', 'ghalya')),
                ghalya_acf_plain_field('home_impact', 'label', __('Label', 'ghalya')),
            ),
        )),

        ghalya_acf_section_tab('deliverables', __('Deliverables', 'ghalya')),
        ghalya_acf_plain_field('home', 'deliver_eyebrow', __('Deliverables eyebrow', 'ghalya')),
        ghalya_acf_plain_field('home', 'deliver_title', __('Deliverables title', 'ghalya')),
        $deliverables_field,

        ghalya_acf_section_tab('eligibility', __('Eligibility', 'ghalya')),
        ghalya_acf_plain_field('home', 'looking_eyebrow', __('Eligibility eyebrow', 'ghalya')),
        ghalya_acf_plain_field('home', 'looking_title', __('Eligibility title', 'ghalya')),
        ghalya_acf_text_rows('home', 'requirements', __('Eligibility requirements', 'ghalya')),
        ghalya_acf_plain_field('home', 'eligibility_terms_title', __('Terms and eligibility heading', 'ghalya')),
        ghalya_acf_plain_field('home', 'eligibility_terms_text', __('Terms and eligibility summary', 'ghalya'), 'textarea', array('rows' => 3, 'new_lines' => '')),
        ghalya_acf_plain_field('home', 'eligibility_terms_link_label', __('Terms-page link label', 'ghalya')),
        ghalya_acf_plain_field('home', 'categories_title', __('Categories title', 'ghalya')),
        ghalya_acf_text_rows('home', 'categories', __('Creator categories', 'ghalya')),
        ghalya_acf_plain_field('home', 'start_button', __('Start button', 'ghalya')),

        ghalya_acf_section_tab('faqs', __('FAQs', 'ghalya')),
        ghalya_acf_plain_field('home', 'faq_eyebrow', __('FAQ eyebrow', 'ghalya')),
        ghalya_acf_plain_field('home', 'faq_title', __('FAQ title', 'ghalya')),
        ghalya_acf_plain_field('home', 'faq_intro', __('FAQ introduction', 'ghalya'), 'textarea', array('rows' => 3, 'new_lines' => '')),
        $faqs_field,
    );
}

function ghalya_register_acf_fields()
{
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    $screen_fields = array(
        'join' => array(
            ghalya_acf_plain_field('join', 'aside_title', __('Sidebar title', 'ghalya')),
            ghalya_acf_plain_field('join', 'aside_intro', __('Sidebar introduction', 'ghalya'), 'textarea', array('rows' => 3, 'new_lines' => '')),
        ),
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
        'page_title' => __('Ghalya Theme Options', 'ghalya'), 'menu_title' => __('Theme Options', 'ghalya'),
        'parent_slug' => 'themes.php', 'menu_slug' => 'ghalya-theme-options', 'capability' => 'manage_options',
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
            array(
                'key' => 'field_ghalya_email_delivery_notice',
                'label' => __('Email delivery', 'ghalya'),
                'type' => 'message',
                'message' => __('Use an authenticated SMTP or transactional email service. Set the sender email below to the same authenticated domain, then publish the SPF, DKIM, and DMARC records supplied by that service.', 'ghalya'),
                'new_lines' => 'wpautop',
                'esc_html' => 1,
            ),
            array('key' => 'field_ghalya_email_from_name', 'label' => __('Sender name', 'ghalya'), 'name' => 'ghalya_email_from_name', 'type' => 'text'),
            array('key' => 'field_ghalya_email_from_address', 'label' => __('Sender email', 'ghalya'), 'name' => 'ghalya_email_from_address', 'type' => 'email', 'instructions' => __('Use the address authenticated by your SMTP service. If blank, the theme uses no-reply at the website domain.', 'ghalya')),
        ),
        'location' => array(array(array('param' => 'options_page', 'operator' => '==', 'value' => 'ghalya-theme-options'))),
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
        'field_ghalya_applicant_email_heading_en' => array('ghalya_applicant_email_heading_en', 'Thank you for applying!'),
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

/** Apply reviewed copy and new field defaults without replacing later admin edits. */
function ghalya_apply_review_content_updates()
{
    if (!function_exists('get_field') || !function_exists('update_field') || get_option('ghalya_review_content_version') === '1') {
        return;
    }

    $page_ids = get_option('ghalya_page_ids', array());

    foreach (array('en', 'ar') as $language) {
        $defaults = ghalya_default_content('home', $language);
        $home_id = isset($page_ids[$language]['home']) ? absint($page_ids[$language]['home']) : 0;

        if ($home_id) {
            $home = get_field('ghalya_home_content', $home_id);

            if (is_array($home)) {
                foreach (array('eligibility_terms_title', 'eligibility_terms_text', 'eligibility_terms_link_label') as $field_name) {
                    if (empty($home[$field_name])) {
                        $home[$field_name] = $defaults[$field_name] ?? '';
                    }
                }

                if ($language === 'ar' && ($home['faq_title'] ?? '') === 'كل ما تحتاج معرفته') {
                    $home['faq_title'] = '';
                }

                if (!empty($home['tiers']) && is_array($home['tiers'])) {
                    $old_amount = $language === 'ar' ? '2,000 ريال' : '2,000 SAR';
                    $migrate_default_amounts = count($home['tiers']) === count($defaults['tiers']);

                    foreach ($home['tiers'] as $existing_tier) {
                        if (($existing_tier['reward_amount'] ?? '') !== $old_amount) {
                            $migrate_default_amounts = false;
                            break;
                        }
                    }

                    foreach ($home['tiers'] as $index => &$tier) {
                        $default_tier = $defaults['tiers'][$index] ?? array();

                        if ($migrate_default_amounts && !empty($default_tier['reward_amount'])) {
                            $tier['reward_amount'] = $default_tier['reward_amount'];
                        }
                    }
                    unset($tier);
                }

                if (!empty($home['faqs']) && is_array($home['faqs'])) {
                    $faq_question = $language === 'ar' ? 'ما هي حقوق المحتوى التي تحصل عليها العلامات؟' : 'What content rights do brands get?';
                    $old_answer = $language === 'ar'
                        ? 'يحتفظ صانع المحتوى بالملكية ويمنح حقوق استخدام محدودة وفقًا للشروط.'
                        : 'Creators retain ownership and grant limited campaign usage rights as described in the terms.';
                    $default_faq_index = array_key_last($defaults['faqs']);
                    $default_faq = $default_faq_index !== null ? $defaults['faqs'][$default_faq_index] : array();

                    foreach ($home['faqs'] as &$faq) {
                        if (($faq['question'] ?? '') !== $faq_question) {
                            continue;
                        }

                        if (($faq['answer'] ?? '') === $old_answer) {
                            $faq['answer'] = $default_faq['answer'] ?? '';
                        }

                        if (empty($faq['terms_link_label'])) {
                            $faq['terms_link_label'] = $default_faq['terms_link_label'] ?? '';
                        }
                    }
                    unset($faq);
                }

                update_field('field_ghalya_home_content', $home, $home_id);
            }
        }

        $tier_id = isset($page_ids[$language]['tier']) ? absint($page_ids[$language]['tier']) : 0;

        if ($tier_id) {
            $tier_content = get_field('ghalya_tier_content', $tier_id);

            if (is_array($tier_content) && empty($tier_content['creator_label'])) {
                $tier_defaults = ghalya_default_content('tier', $language);
                $tier_content['creator_label'] = $tier_defaults['creator_label'] ?? '';
                update_field('field_ghalya_tier_content', $tier_content, $tier_id);
            }
        }

        $success_id = isset($page_ids[$language]['success']) ? absint($page_ids[$language]['success']) : 0;

        if ($success_id) {
            $success = get_field('ghalya_success_content', $success_id);
            $old_message = 'Thank you for your interest in joining the Ghalya community. Expect a reply within 14 days.';

            if ($language === 'en' && is_array($success) && ($success['message'] ?? '') === $old_message) {
                $success_defaults = ghalya_default_content('success', 'en');
                $success['message'] = $success_defaults['message'] ?? '';
                update_field('field_ghalya_success_content', $success, $success_id);
            }
        }
    }

    $terms_id = isset($page_ids['ar']['terms']) ? absint($page_ids['ar']['terms']) : 0;

    if ($terms_id) {
        $terms = get_field('ghalya_terms_content', $terms_id);

        if (is_array($terms) && !empty($terms['sections']) && is_array($terms['sections'])) {
            foreach ($terms['sections'] as &$section) {
                $section['title'] = str_replace('تسليمات', 'مخرجات', (string) ($section['title'] ?? ''));
                $section['body'] = str_replace('التسليمات', 'المخرجات', (string) ($section['body'] ?? ''));
            }
            unset($section);
            update_field('field_ghalya_terms_content', $terms, $terms_id);
        }
    }

    $email_heading = get_field('ghalya_applicant_email_heading_en', 'option');

    if ($email_heading === 'Thank you for applying') {
        update_field('field_ghalya_applicant_email_heading_en', 'Thank you for applying!', 'option');
    }

    update_option('ghalya_review_content_version', '1', false);
}
add_action('admin_init', 'ghalya_apply_review_content_updates', 60);

/** Populate the new logo repeater with the bundled partner logos once. */
function ghalya_seed_partner_logos()
{
    if (!function_exists('get_field') || !function_exists('update_field') || get_option('ghalya_partner_logos_version') === '1') {
        return;
    }

    $attachments = get_option('ghalya_media_attachments', array());
    $logo_files = array(
        'delsey-paris.png' => 'Delsey Paris',
        'kipling.png' => 'Kipling',
        'danube.png' => 'Danube',
        'zahrat-alrawdah.png' => 'Zahrat Al Rawdah',
        'bindawood.png' => 'BinDawood',
    );
    $rows = array();

    foreach ($logo_files as $filename => $name) {
        $attachment_id = isset($attachments[$filename]) ? absint($attachments[$filename]) : 0;

        if (!$attachment_id || get_post_type($attachment_id) !== 'attachment') {
            return;
        }

        $rows[] = array('logo' => $attachment_id, 'name' => $name);
    }

    $page_ids = get_option('ghalya_page_ids', array());
    $found_home_page = false;

    foreach (array('en', 'ar') as $language) {
        $home_id = isset($page_ids[$language]['home']) ? absint($page_ids[$language]['home']) : 0;

        if (!$home_id || !get_post_status($home_id)) {
            continue;
        }

        $found_home_page = true;
        $home = get_field('ghalya_home_content', $home_id);

        if (is_array($home) && empty($home['partner_logos'])) {
            $home['partner_logos'] = $rows;
            update_field('field_ghalya_home_content', $home, $home_id);
        }
    }

    if ($found_home_page) {
        update_option('ghalya_partner_logos_version', '1', false);
    }
}
add_action('admin_init', 'ghalya_seed_partner_logos', 70);
