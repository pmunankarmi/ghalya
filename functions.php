<?php

if (!defined('ABSPATH')) {
    exit;
}

define('GHALYA_THEME_VERSION', '1.2.0');
define('GHALYA_THEME_PATH', get_template_directory());
define('GHALYA_THEME_URI', get_template_directory_uri());

require_once GHALYA_THEME_PATH . '/inc/helpers.php';
require_once GHALYA_THEME_PATH . '/inc/setup.php';
require_once GHALYA_THEME_PATH . '/inc/acf.php';
require_once GHALYA_THEME_PATH . '/inc/submissions.php';
