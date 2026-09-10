<?php

if (!defined('ABSPATH')) {
    exit;
}

function ghalya_register_submission_post_type()
{
    register_post_type('ghalya_submission', array(
        'labels' => array(
            'name' => __('Ghalya Submissions', 'ghalya'),
            'singular_name' => __('Submission', 'ghalya'),
            'menu_name' => __('Ghalya Submissions', 'ghalya'),
            'all_items' => __('All submissions', 'ghalya'),
            'edit_item' => __('Review submission', 'ghalya'),
            'search_items' => __('Search submissions', 'ghalya'),
            'not_found' => __('No submissions found.', 'ghalya'),
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-forms',
        'supports' => false,
        'capabilities' => array(
            'edit_post' => 'manage_options',
            'read_post' => 'manage_options',
            'delete_post' => 'manage_options',
            'edit_posts' => 'manage_options',
            'edit_others_posts' => 'manage_options',
            'publish_posts' => 'manage_options',
            'read_private_posts' => 'manage_options',
            'delete_posts' => 'manage_options',
            'delete_private_posts' => 'manage_options',
            'delete_published_posts' => 'manage_options',
            'delete_others_posts' => 'manage_options',
            'edit_private_posts' => 'manage_options',
            'edit_published_posts' => 'manage_options',
            'create_posts' => 'do_not_allow',
        ),
        'map_meta_cap' => false,
    ));
}
add_action('init', 'ghalya_register_submission_post_type');

function ghalya_submission_error_message($error_code)
{
    $language = ghalya_current_language();
    $messages = array(
        'security' => array('en' => 'Your session expired. Please review the form and submit it again.', 'ar' => 'انتهت صلاحية الجلسة. يرجى مراجعة النموذج وإرساله مرة أخرى.'),
        'invalid' => array('en' => 'Some required application details are missing or invalid.', 'ar' => 'بعض بيانات الطلب المطلوبة مفقودة أو غير صحيحة.'),
        'save' => array('en' => 'We could not save your application. Please try again.', 'ar' => 'تعذر حفظ طلبك. يرجى المحاولة مرة أخرى.'),
    );

    $error_code = isset($messages[$error_code]) ? $error_code : 'invalid';
    return $messages[$error_code][$language];
}

function ghalya_redirect_submission_error($language, $error_code)
{
    $url = add_query_arg('submission_error', sanitize_key($error_code), ghalya_page_url('contact', $language));
    wp_safe_redirect($url);
    exit;
}

function ghalya_sanitize_application_value($value)
{
    if (is_array($value)) {
        return array_map('ghalya_sanitize_application_value', $value);
    }

    return sanitize_text_field((string) $value);
}

function ghalya_application_step($application, $language, $step_number)
{
    $key = $language . ':mt-step-' . absint($step_number);
    return isset($application[$key]) && is_array($application[$key]) ? $application[$key] : array();
}

function ghalya_application_is_complete($application, $language, $email, $phone)
{
    $profile = ghalya_application_step($application, $language, 1);
    $work = ghalya_application_step($application, $language, 3);
    $proposal = ghalya_application_step($application, $language, 4);

    $profile_fields = array('full_name', 'instagram', 'followers', 'city');
    $work_fields = array('instagram_url', 'tiktok_url', 'snapchat', 'brand_content_url');

    foreach ($profile_fields as $field) {
        if (empty($profile[$field])) {
            return false;
        }
    }

    foreach ($work_fields as $field) {
        if (empty($work[$field])) {
            return false;
        }
    }

    if (empty($profile['content_categories']) || !is_array($profile['content_categories'])) {
        return false;
    }

    if (empty($proposal['brands']) || !is_array($proposal['brands']) || empty($proposal['availability'])) {
        return false;
    }

    return is_email($email) && strlen($phone) >= 8 && strlen($phone) <= 40;
}

function ghalya_readable_label($value)
{
    return ucwords(str_replace(array('_', '-'), ' ', (string) $value));
}

function ghalya_format_application_email($application)
{
    $lines = array();

    foreach ($application as $step => $fields) {
        if (!is_array($fields)) {
            continue;
        }

        $lines[] = ghalya_readable_label($step);
        $lines[] = str_repeat('-', 44);

        foreach ($fields as $name => $value) {
            if (is_array($value)) {
                $value = implode(', ', array_map('strval', $value));
            }

            $lines[] = ghalya_readable_label($name) . ': ' . (string) $value;
        }

        $lines[] = '';
    }

    return implode("\n", $lines);
}

function ghalya_submission_recipient()
{
    $recipient = '';

    if (function_exists('get_field')) {
        $recipient = (string) get_field('ghalya_submission_email', 'option');
    }

    return is_email($recipient) ? $recipient : get_option('admin_email');
}

function ghalya_handle_application_submission()
{
    $language = isset($_POST['language']) && wp_unslash($_POST['language']) === 'ar' ? 'ar' : 'en';

    if (!isset($_POST['_ghalya_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ghalya_nonce'])), 'ghalya_submit_application')) {
        ghalya_redirect_submission_error($language, 'security');
    }

    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';
    $terms_accepted = !empty($_POST['terms']);
    $application_json = isset($_POST['application_data']) ? wp_unslash($_POST['application_data']) : '';

    if ($application_json === '' || strlen($application_json) > 100000) {
        ghalya_redirect_submission_error($language, 'invalid');
    }

    $application = json_decode($application_json, true, 20);

    if (!is_array($application)) {
        ghalya_redirect_submission_error($language, 'invalid');
    }

    $application = ghalya_sanitize_application_value($application);

    if (!$terms_accepted || !ghalya_application_is_complete($application, $language, $email, $phone)) {
        ghalya_redirect_submission_error($language, 'invalid');
    }

    $contact_key = $language . ':mt-step-5';
    $application[$contact_key]['email'] = $email;
    $application[$contact_key]['phone'] = $phone;
    $application[$contact_key]['terms'] = array('accepted');

    $profile = ghalya_application_step($application, $language, 1);
    $full_name = sanitize_text_field($profile['full_name']);
    $post_id = wp_insert_post(array(
        'post_type' => 'ghalya_submission',
        'post_status' => 'private',
        'post_title' => sprintf('%s — %s', $full_name, current_time('Y-m-d H:i')),
    ), true);

    if (is_wp_error($post_id)) {
        ghalya_redirect_submission_error($language, 'save');
    }

    update_post_meta($post_id, '_ghalya_application', $application);
    update_post_meta($post_id, '_ghalya_language', $language);
    update_post_meta($post_id, '_ghalya_email', $email);
    update_post_meta($post_id, '_ghalya_phone', $phone);
    update_post_meta($post_id, 'ghalya_status', 'new');

    $subject = 'New Ghalya creator application';

    if (function_exists('get_field')) {
        $custom_subject = trim((string) get_field('ghalya_email_subject', 'option'));
        $subject = $custom_subject !== '' ? $custom_subject : $subject;
    }

    $headers = array('Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $email);
    $mail_sent = wp_mail(ghalya_submission_recipient(), $subject, ghalya_format_application_email($application), $headers);
    update_post_meta($post_id, '_ghalya_mail_status', $mail_sent ? 'sent' : 'failed');

    // The application is safely stored in WordPress, so its browser copy can be removed.
    setcookie('mt_ghalya_application', '', array(
        'expires' => time() - 3600,
        'path' => '/',
        'secure' => is_ssl(),
        'httponly' => false,
        'samesite' => 'Lax',
    ));

    wp_safe_redirect(add_query_arg('submitted', '1', ghalya_page_url('success', $language)), 303);
    exit;
}
add_action('admin_post_nopriv_ghalya_submit_application', 'ghalya_handle_application_submission');
add_action('admin_post_ghalya_submit_application', 'ghalya_handle_application_submission');

function ghalya_submission_details_box($post)
{
    $application = get_post_meta($post->ID, '_ghalya_application', true);

    if (!is_array($application)) {
        echo '<p>' . esc_html__('No application data is available.', 'ghalya') . '</p>';
        return;
    }

    foreach ($application as $step => $fields) {
        if (!is_array($fields)) {
            continue;
        }

        echo '<h3>' . esc_html(ghalya_readable_label($step)) . '</h3>';
        echo '<table class="widefat striped"><tbody>';

        foreach ($fields as $name => $value) {
            $display_value = is_array($value) ? implode(', ', $value) : $value;
            echo '<tr><th style="width:220px">' . esc_html(ghalya_readable_label($name)) . '</th><td>' . esc_html($display_value) . '</td></tr>';
        }

        echo '</tbody></table>';
    }
}

function ghalya_add_submission_details_box()
{
    add_meta_box('ghalya-submission-details', __('Application details', 'ghalya'), 'ghalya_submission_details_box', 'ghalya_submission', 'normal', 'high');
}
add_action('add_meta_boxes_ghalya_submission', 'ghalya_add_submission_details_box');

function ghalya_submission_columns($columns)
{
    return array(
        'cb' => isset($columns['cb']) ? $columns['cb'] : '<input type="checkbox" />',
        'title' => __('Applicant', 'ghalya'),
        'ghalya_email' => __('Email', 'ghalya'),
        'ghalya_phone' => __('Phone', 'ghalya'),
        'ghalya_language' => __('Language', 'ghalya'),
        'ghalya_status' => __('Status', 'ghalya'),
        'ghalya_mail' => __('Email delivery', 'ghalya'),
        'date' => __('Submitted', 'ghalya'),
    );
}
add_filter('manage_ghalya_submission_posts_columns', 'ghalya_submission_columns');

function ghalya_submission_column_value($column, $post_id)
{
    $meta_keys = array(
        'ghalya_email' => '_ghalya_email',
        'ghalya_phone' => '_ghalya_phone',
        'ghalya_language' => '_ghalya_language',
        'ghalya_status' => 'ghalya_status',
        'ghalya_mail' => '_ghalya_mail_status',
    );

    if (isset($meta_keys[$column])) {
        echo esc_html(get_post_meta($post_id, $meta_keys[$column], true));
    }
}
add_action('manage_ghalya_submission_posts_custom_column', 'ghalya_submission_column_value', 10, 2);

function ghalya_csv_export_button($which)
{
    if ($which !== 'top' || get_current_screen()->post_type !== 'ghalya_submission' || !current_user_can('manage_options')) {
        return;
    }

    $url = wp_nonce_url(admin_url('admin-post.php?action=ghalya_export_submissions'), 'ghalya_export_submissions');
    echo '<a class="button button-primary" href="' . esc_url($url) . '" style="margin:1px 8px 0 0">' . esc_html__('Export CSV', 'ghalya') . '</a>';
}
add_action('manage_posts_extra_tablenav', 'ghalya_csv_export_button');

function ghalya_export_value($application, $language, $step, $field)
{
    $fields = ghalya_application_step($application, $language, $step);
    $value = isset($fields[$field]) ? $fields[$field] : '';
    return is_array($value) ? implode(' | ', $value) : $value;
}

function ghalya_export_previous_content($application, $language)
{
    $work = ghalya_application_step($application, $language, 3);
    $links = array();

    foreach ($work as $name => $value) {
        if ($name === 'brand_content_url' || strpos($name, 'brand_content_url_') === 0) {
            $links[] = $value;
        }
    }

    return implode(' | ', array_filter($links));
}

function ghalya_export_submissions()
{
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have permission to export submissions.', 'ghalya'));
    }

    check_admin_referer('ghalya_export_submissions');

    $submissions = get_posts(array(
        'post_type' => 'ghalya_submission',
        'post_status' => array('private', 'publish'),
        'numberposts' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
    ));

    nocache_headers();
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="ghalya-submissions-' . gmdate('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');
    fwrite($output, "\xEF\xBB\xBF");
    fputcsv($output, array('ID', 'Submitted', 'Status', 'Language', 'Full name', 'Email', 'Phone', 'Instagram handle', 'Followers', 'City', 'Categories', 'Instagram URL', 'TikTok URL', 'Snapchat', 'Previous content', 'Brands', 'Availability', 'Mail status', 'Reviewer notes'));

    foreach ($submissions as $submission) {
        $application = get_post_meta($submission->ID, '_ghalya_application', true);
        $language = get_post_meta($submission->ID, '_ghalya_language', true) ?: 'en';
        $application = is_array($application) ? $application : array();

        fputcsv($output, array(
            $submission->ID,
            get_post_time('Y-m-d H:i:s', false, $submission),
            get_post_meta($submission->ID, 'ghalya_status', true),
            $language,
            ghalya_export_value($application, $language, 1, 'full_name'),
            get_post_meta($submission->ID, '_ghalya_email', true),
            get_post_meta($submission->ID, '_ghalya_phone', true),
            ghalya_export_value($application, $language, 1, 'instagram'),
            ghalya_export_value($application, $language, 1, 'followers'),
            ghalya_export_value($application, $language, 1, 'city'),
            ghalya_export_value($application, $language, 1, 'content_categories'),
            ghalya_export_value($application, $language, 3, 'instagram_url'),
            ghalya_export_value($application, $language, 3, 'tiktok_url'),
            ghalya_export_value($application, $language, 3, 'snapchat'),
            ghalya_export_previous_content($application, $language),
            ghalya_export_value($application, $language, 4, 'brands'),
            ghalya_export_value($application, $language, 4, 'availability'),
            get_post_meta($submission->ID, '_ghalya_mail_status', true),
            get_post_meta($submission->ID, 'ghalya_reviewer_notes', true),
        ));
    }

    fclose($output);
    exit;
}
add_action('admin_post_ghalya_export_submissions', 'ghalya_export_submissions');
