<?php

if (!defined('ABSPATH')) {
    exit;
}

/** Bundled originals are installation seeds; rendered URLs use Media Library IDs. */
function ghalya_media_files()
{
    return glob(GHALYA_THEME_PATH . '/inc/media/*.{png,jpg,jpeg,gif,webp,avif,svg,ico}', GLOB_BRACE);
}

function ghalya_media_url($filename)
{
    $filename = basename($filename);
    $attachments = get_option('ghalya_media_attachments', array());
    $id = isset($attachments[$filename]) ? absint($attachments[$filename]) : 0;
    $url = $id && get_post_status($id) !== 'trash' ? wp_get_attachment_url($id) : false;

    // Keep the site usable before the first administrator visit or after an import error.
    return $url ? $url : GHALYA_THEME_URI . '/inc/media/' . rawurlencode($filename);
}

/** Import only trusted files shipped with this theme, including its SVG icons. */
function ghalya_import_media()
{
    if (!current_user_can('manage_options') || !current_user_can('upload_files')) {
        return;
    }

    $version = '1';
    if (get_option('ghalya_media_version') === $version) {
        return;
    }

    // An atomic option prevents simultaneous administrator requests duplicating media.
    $lock = (int) get_option('ghalya_media_import_lock');
    if ($lock && $lock < time() - 300) {
        delete_option('ghalya_media_import_lock');
    }
    if (!add_option('ghalya_media_import_lock', time(), '', false)) {
        return;
    }

    try {
        $uploads = wp_upload_dir();
        if ($uploads['error']) {
            update_option('ghalya_media_errors', array($uploads['error']), false);
            return;
        }

        require_once ABSPATH . 'wp-admin/includes/image.php';
        $attachments = get_option('ghalya_media_attachments', array());
        $errors = array();
        $files = ghalya_media_files();
        if (!$files) {
            update_option('ghalya_media_errors', array(__('Bundled media files are missing.', 'ghalya')), false);
            return;
        }

        foreach ($files as $source) {
            $filename = basename($source);
            $id = isset($attachments[$filename]) ? absint($attachments[$filename]) : 0;
            if ($id && get_post_type($id) === 'attachment' && get_post_status($id) !== 'trash') {
                continue;
            }

            // Recover a previously registered attachment if an earlier import was interrupted.
            $existing = get_posts(array(
                'post_type' => 'attachment',
                'post_status' => 'inherit',
                'meta_key' => '_ghalya_media_source',
                'meta_value' => $filename,
                'fields' => 'ids',
                'posts_per_page' => 1,
                'suppress_filters' => true,
            ));
            if ($existing) {
                $attachments[$filename] = (int) $existing[0];
                update_option('ghalya_media_attachments', $attachments, false);
                continue;
            }

            $destination = trailingslashit($uploads['path']) . wp_unique_filename($uploads['path'], $filename);
            if (!copy($source, $destination)) {
                $errors[] = sprintf(__('Could not copy %s to uploads.', 'ghalya'), $filename);
                continue;
            }

            $type = wp_check_filetype($filename, array(
                'png' => 'image/png', 'jpg|jpeg' => 'image/jpeg', 'gif' => 'image/gif',
                'webp' => 'image/webp', 'avif' => 'image/avif', 'svg' => 'image/svg+xml', 'ico' => 'image/x-icon',
            ));
            $id = wp_insert_attachment(array(
                'post_mime_type' => $type['type'],
                'post_title' => ucwords(str_replace(array('-', '_'), ' ', pathinfo($filename, PATHINFO_FILENAME))),
                'post_status' => 'inherit',
                'meta_input' => array('_ghalya_media_source' => $filename),
            ), $destination, 0, true);

            if (is_wp_error($id) || !$id) {
                wp_delete_file($destination);
                $errors[] = sprintf(__('Could not register %s in the Media Library.', 'ghalya'), $filename);
                continue;
            }

            // Persist each ID immediately so retries keep all successful imports.
            $attachments[$filename] = (int) $id;
            update_option('ghalya_media_attachments', $attachments, false);
            if ($type['type'] !== 'image/svg+xml') {
                $metadata = wp_generate_attachment_metadata($id, $destination);
                if ($metadata) {
                    wp_update_attachment_metadata($id, $metadata);
                }
            }
        }

        update_option('ghalya_media_errors', $errors, false);
        if (!$errors) {
            update_option('ghalya_media_version', $version, false);
        }
    } finally {
        delete_option('ghalya_media_import_lock');
    }
}
add_action('admin_init', 'ghalya_import_media', 5);
add_action('after_switch_theme', 'ghalya_import_media', 5);

/** CSS icons resolve through the same attachment IDs as template images. */
function ghalya_enqueue_media_styles()
{
    $css = ':root {';
    foreach (array('check-icon', 'plus-icon', 'close-icon', 'chevron-left') as $icon) {
        $css .= '--ghalya-' . $icon . ':url("' . esc_url(ghalya_media_url($icon . '.svg')) . '");';
    }
    wp_add_inline_style('ghalya-theme', $css . '}');
}

function ghalya_media_import_notice()
{
    $errors = get_option('ghalya_media_errors', array());
    if ($errors && current_user_can('manage_options')) {
        echo '<div class="notice notice-error"><p>';
        echo esc_html__('Ghalya media import is incomplete and will retry on your next admin visit. Check that the uploads directory is writable.', 'ghalya');
        echo ' ' . esc_html(implode(' ', $errors));
        echo '</p></div>';
    }
}
add_action('admin_notices', 'ghalya_media_import_notice');

/** Restore required theme assets on the next admin visit if one is deleted. */
function ghalya_invalidate_deleted_media($id)
{
    if (get_post_meta($id, '_ghalya_media_source', true)) {
        delete_option('ghalya_media_version');
    }
}
add_action('delete_attachment', 'ghalya_invalidate_deleted_media');
add_action('trashed_post', 'ghalya_invalidate_deleted_media');
