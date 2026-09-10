# Ghalya WordPress theme

A bilingual WordPress conversion of the Ghalya creator-program HTML pack.

## Requirements

- WordPress 6.4 or newer
- PHP 7.4 or newer
- ACF Pro for landing-page fields, email settings, and submission review fields
- Polylang for English/Arabic page relationships and language switching
- A configured WordPress mail transport; an SMTP plugin is recommended in production

## Installation

1. Upload `ghalya-wordpress-theme.zip` in **Appearance → Themes → Add New → Upload Theme**.
2. Activate ACF Pro and Polylang, then activate the theme.
3. The theme creates connected English and Arabic pages for the landing page, five form steps, success page, and terms page.
4. In Polylang, confirm that English and Arabic are configured and choose the translated Ghalya landing pages as the front page if required.
5. Set the form recipient under **Ghalya Submissions → Email settings**. If blank, the WordPress administration email is used.

## Theme updates

Version 1.3.0 adds update checks through the WordPress Themes screen. Install this
version manually once, then future published GitHub releases will appear as normal
theme updates in WordPress.

Each release must use a semantic version tag such as `v1.3.0` and include the
installable asset `ghalya-wordpress-theme.zip`. WordPress checks the latest public
release approximately every six hours; **Dashboard → Updates → Check again** can
be used to request a fresh check.

## Submissions

Completed applications are stored as private records under **Ghalya Submissions**. Each record includes the complete multi-step form, review status, reviewer notes, and email-delivery status.

Use **Export CSV** above the submissions table to download all records. The export is restricted to administrators and protected by a WordPress nonce.

## Content editing

Select the site logo under **Appearance → Customize → Site Identity**. The bundled Ghalya logo remains as the fallback until a custom logo is selected.

The dedicated **Ghalya Home Page** template and every application, success, and terms page receive a **Ghalya Page Content** ACF field. The installer imports the supplied design into that field once; front-end templates then read the saved ACF value rather than hardcoded page copy. English and Arabic pages keep independent content through Polylang.

Header labels are editable under **Appearance → Ghalya content**. Gutenberg and the standard page content editor are disabled because this theme uses ACF as its page-editing interface.

The original supplied HTML is stored under `content-seed/` only for first-time page population. Runtime templates do not read hardcoded page copy; they render each page's saved ACF content.
