# Ghalya WordPress theme

A bilingual WordPress conversion of the Ghalya creator-program HTML pack.

## Requirements

- WordPress 6.4 or newer
- PHP 7.4 or newer
- ACF Pro for landing-page fields, email settings, and submission review fields
- Polylang for English/Arabic page relationships and language switching; same-slug compatibility is bundled with the theme
- A configured WordPress mail transport; an SMTP plugin is recommended in production

## Installation

1. Upload `ghalya-wordpress-theme.zip` in **Appearance → Themes → Add New → Upload Theme**.
2. Activate ACF Pro and Polylang, then activate the theme.
3. The theme imports all 25 bundled images and SVG icons into the WordPress Media Library and creates connected English and Arabic pages for the landing page, Join the Community parent, five child form steps, success page, and terms page.
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

Select the site logo under **Appearance → Customize → Site Identity**. The imported Ghalya logo remains as the fallback until a custom logo is selected.

The dedicated **Ghalya Home Page** template and every application, success, and terms page receive screen-specific ACF fields. Homepage fields are grouped into section tabs, and each Benefits or Deliverables card has its own optional icon image selector. Administrators edit plain text, labels, choices, and repeatable items such as benefits, FAQs, and terms sections. All HTML remains in the theme's `template-parts` files. English and Arabic pages keep independent content through Polylang.

Creator tier labels and reward amounts are edited on each translated Home page under **Hero & Rewards → Creator tiers**. The tier selected by the visitor on the homepage is carried into the application and its configured amount appears on the **Your Tier** step. The amount and tier are also saved with the submission. The reward description remains shared across all homepage tabs, while only the configured tier amount changes.

The separate **Appearance → Ghalya content** page is not used. The application progress list is built from the direct child pages beneath **Join the Community**, ordered by WordPress menu order, so each page title is managed in the standard page title field. The shared sidebar title and introduction are edited once on the translated **Join the Community** parent page. Shared application labels such as Previous, Back, Continue, and Submit application are managed under **Languages → Translations**. The language switcher displays the native language name configured under **Languages → Languages**. Gutenberg and the standard page content editor are disabled because this theme uses ACF as its page-editing interface.

English and Arabic translations use the same page slug. Polylang's language segment distinguishes each URL, while the bundled Polylang Slug compatibility layer prevents WordPress from adding suffixes such as `-2` or `-ar`. Existing generated pages are migrated automatically on the next administrator visit.

Notification subjects support `{name}`, `{email}`, and `{submission_id}` tokens. The branded responsive email uses the Customizer site logo and sends an application summary to administrators plus an English or Arabic confirmation to the applicant.

### Email delivery

Configure an authenticated SMTP or transactional email plugin in WordPress. In **Ghalya Submissions → Notifications**, use a sender address on the same domain authenticated by that service. Add the provider's SPF and DKIM records to DNS and publish a DMARC record for the sending domain. The theme supplies an aligned website-domain fallback sender and multipart HTML/plain-text content, but DNS authentication and mail transport must be configured by the hosting or email provider.

## Media Library migration (1.4.7)

After installing this update, visit WordPress administration as an administrator with upload permissions. The theme copies all 25 originals from `inc/media` to the configured WordPress uploads directory and registers each as a Media Library attachment. Template images, email logos, and CSS icons resolve to those attachment URLs. Existing custom logos and ACF image choices are preserved.

The import runs on activation or the first administrator visit after updating. Successful imports are recorded by attachment ID so subsequent visits do not duplicate them. Failed imports show an admin notice and retry on the next visit. Deleting a theme-owned attachment causes it to be restored on the next admin visit because the theme still needs it.

The `inc/media` folder contains installation originals and temporary fallbacks, allowing fresh installations and recovery when uploads fail. Images are no longer served from `assets/images`. SVG import is limited to these trusted bundled files; general SVG uploads are not enabled. Fonts, scripts, and styles remain theme assets.
