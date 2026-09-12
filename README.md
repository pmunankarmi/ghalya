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
5. Configure administrator and applicant emails under **Ghalya Submissions → Notifications**. If the recipient is blank, the WordPress administration email is used.

## Theme updates

Version 1.3.0 and newer include update checks through the WordPress Themes screen.
If the installed theme is older than 1.3.0, install a current release manually once;
future published GitHub releases will then appear as normal theme updates in WordPress.

Each release must use a semantic version tag such as `v1.3.0` and include the
installable asset `ghalya-wordpress-theme.zip`. WordPress checks the latest public
release approximately every six hours; **Dashboard → Updates → Check again** can
be used to request a fresh check.

## Submissions

Completed applications are stored as private records under **Ghalya Submissions**. Each record includes the complete multi-step form, review status, reviewer notes, administrator-email status, and applicant-confirmation status.

Use **Export CSV** above the submissions table to download all records. The export is restricted to administrators and protected by a WordPress nonce.

## Content editing

Select the site logo under **Appearance → Customize → Site Identity**. The bundled Ghalya logo remains as the fallback until a custom logo is selected.

The dedicated **Ghalya Home Page** template and every application, success, and terms page receive screen-specific ACF fields. Administrators edit plain text, labels, choices, and repeatable items such as benefits, FAQs, and terms sections. All HTML remains in the theme's `template-parts` files. English and Arabic pages keep independent content through Polylang.

The separate **Appearance → Ghalya content** page is not used. Shared navigation labels use the theme's WordPress translation files, while the language switcher displays the native language name configured under **Languages → Languages**. Gutenberg and the standard page content editor are disabled because this theme uses ACF as its page-editing interface.

Notification subjects support `{name}`, `{email}`, and `{submission_id}` tokens. The branded responsive email uses the Customizer site logo and sends an application summary to administrators plus an English or Arabic confirmation to the applicant.
