<?php
/**
 * Template Name: Ghalya Join Parent
 *
 * The parent page keeps the application steps grouped in WordPress. Visitors
 * entering the parent URL continue directly to the first application step.
 */

if (!defined('ABSPATH')) {
    exit;
}

wp_safe_redirect(ghalya_page_url('profile', ghalya_current_language()));
exit;
