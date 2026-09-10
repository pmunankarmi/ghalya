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

## Submissions

Completed applications are stored as private records under **Ghalya Submissions**. Each record includes the complete multi-step form, review status, reviewer notes, and email-delivery status.

Use **Export CSV** above the submissions table to download all records. The export is restricted to administrators and protected by a WordPress nonce.

## Content editing

The Ghalya landing template exposes its hero title, highlighted word, description, and join-button label through ACF. Leave a field blank to retain the supplied design copy. English and Arabic landing pages keep independent field values through Polylang.

