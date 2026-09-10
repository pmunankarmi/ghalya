<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Read the latest public release from GitHub.
 *
 * WordPress already caches theme-update checks. A second theme-specific cache
 * would prevent the Dashboard's "Check again" action from seeing a new release.
 *
 * The release must contain an asset named ghalya-wordpress-theme.zip. Keeping
 * that filename stable ensures WordPress receives the correct theme folder.
 *
 * @return array|false
 */
function ghalya_get_latest_github_release() {
    $response = wp_remote_get(
        'https://api.github.com/repos/pmunankarmi/ghalya/releases/latest',
        array(
            'headers' => array(
                'Accept' => 'application/vnd.github+json',
                'User-Agent' => 'Ghalya-WordPress-Theme/' . GHALYA_THEME_VERSION,
            ),
            'timeout' => 10,
        )
    );

    if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
        return false;
    }

    $release = json_decode(wp_remote_retrieve_body($response), true);

    if (
        !is_array($release)
        || !empty($release['draft'])
        || !empty($release['prerelease'])
        || empty($release['tag_name'])
    ) {
        return false;
    }

    $version = ltrim((string) $release['tag_name'], 'vV');

    if (!preg_match('/^\d+\.\d+\.\d+(?:[-+][0-9A-Za-z.-]+)?$/', $version)) {
        return false;
    }

    $package_url = '';

    foreach ((array) ($release['assets'] ?? array()) as $asset) {
        if (
            isset($asset['name'], $asset['browser_download_url'])
            && 'ghalya-wordpress-theme.zip' === $asset['name']
        ) {
            $package_url = esc_url_raw($asset['browser_download_url']);
            break;
        }
    }

    if ('' === $package_url) {
        return false;
    }

    return array(
        'version' => $version,
        'url' => esc_url_raw($release['html_url'] ?? 'https://github.com/pmunankarmi/ghalya/releases'),
        'package' => $package_url,
    );
}

/**
 * Supply update details for this theme through WordPress' Update URI hook.
 *
 * @param array|false $update     Existing update data.
 * @param array       $theme_data Parsed theme headers.
 * @param string      $stylesheet Theme directory name.
 * @param array       $locales    Requested locales.
 * @return array|false
 */
function ghalya_filter_github_theme_update($update, $theme_data, $stylesheet, $locales) {
    unset($locales);

    $update_uri = isset($theme_data['UpdateURI']) ? untrailingslashit($theme_data['UpdateURI']) : '';

    if ('https://github.com/pmunankarmi/ghalya' !== $update_uri) {
        return $update;
    }

    $release = ghalya_get_latest_github_release();

    if (false === $release) {
        return $update;
    }

    return array(
        'id' => 'https://github.com/pmunankarmi/ghalya',
        'theme' => $stylesheet,
        'version' => $release['version'],
        'url' => $release['url'],
        'package' => $release['package'],
        'requires_php' => '7.4',
        'autoupdate' => false,
    );
}
add_filter('update_themes_github.com', 'ghalya_filter_github_theme_update', 10, 4);
