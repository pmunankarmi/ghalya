<?php
$content = isset($args['content']) && is_array($args['content']) ? $args['content'] : array();
?>
<main class="mt-terms-page">
  <div class="container">
    <article class="mt-terms-card" data-aos="fade-up">
      <p class="mt-eyebrow"><?php echo esc_html(ghalya_content_text($content, 'eyebrow')); ?></p>
      <h1><?php echo esc_html(ghalya_content_text($content, 'title')); ?></h1>
      <p class="mt-form-intro"><?php echo esc_html(ghalya_content_text($content, 'intro')); ?></p>
      <?php foreach (ghalya_content_rows($content, 'sections') as $section) : ?>
        <h2><?php echo esc_html($section['title'] ?? ''); ?></h2>
        <p><?php echo esc_html($section['body'] ?? ''); ?></p>
      <?php endforeach; ?>
      <div class="d-flex flex-wrap gap-3 pt-4">
        <a class="mt-btn-primary" href="<?php echo esc_url(ghalya_page_url('profile')); ?>"><?php echo esc_html(ghalya_content_text($content, 'start_button')); ?></a>
        <a class="mt-btn-secondary" href="<?php echo esc_url(ghalya_page_url('home')); ?>"><?php echo esc_html(ghalya_content_text($content, 'back_button')); ?></a>
      </div>
    </article>
  </div>
</main>
