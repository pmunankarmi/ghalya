<?php
$ghalya_language = ghalya_current_language();
$ghalya_screen = ghalya_current_screen();
$ghalya_is_landing = $ghalya_screen === 'home';
?>
<!doctype html>
<html lang="<?php echo esc_attr($ghalya_language); ?>" dir="<?php echo $ghalya_language === 'ar' ? 'rtl' : 'ltr'; ?>">
  <head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <header class="mt-site-header">
      <nav class="navbar<?php echo $ghalya_is_landing ? ' navbar-expand-lg' : ''; ?>">
        <div class="container">
          <a class="mt-brand" href="<?php echo esc_url(ghalya_page_url('home', $ghalya_language)); ?>" aria-label="<?php esc_attr_e('Ghalya home', 'ghalya'); ?>">
            <img src="<?php echo esc_url(GHALYA_THEME_URI . '/assets/images/ghalya-logo.png'); ?>" alt="Ghalya" />
          </a>
          <div class="d-flex align-items-center <?php echo $ghalya_is_landing ? 'gap-4' : 'gap-3'; ?>">
            <?php if ($ghalya_is_landing) : ?>
              <a class="mt-nav-link d-none d-md-inline" href="#benefits"><?php echo $ghalya_language === 'ar' ? 'المزايا' : 'Benefits'; ?></a>
              <a class="mt-nav-link d-none d-md-inline" href="#faq"><?php echo $ghalya_language === 'ar' ? 'الأسئلة الشائعة' : 'FAQs'; ?></a>
            <?php endif; ?>
            <a class="mt-nav-link d-none d-sm-inline" href="<?php echo esc_url(ghalya_page_url('terms', $ghalya_language)); ?>"><?php echo $ghalya_language === 'ar' ? 'الشروط' : 'Terms'; ?></a>
            <a class="mt-lang-link" href="<?php echo esc_url(ghalya_language_switch_url()); ?>"><?php echo $ghalya_language === 'ar' ? 'English' : 'العربية'; ?></a>
          </div>
        </div>
      </nav>
    </header>

