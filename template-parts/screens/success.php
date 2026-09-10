<?php
$content = isset($args['content']) && is_array($args['content']) ? $args['content'] : array();
?>
<main class="mt-form-page">
  <div class="container">
    <section class="mt-form-card mt-success-wrap" data-aos="fade-up">
      <div>
        <div class="mt-success-icon"><img src="<?php echo esc_url(ghalya_asset_url('images/check-icon.svg')); ?>" alt="" /></div>
        <p class="mt-eyebrow"><?php echo esc_html(ghalya_content_text($content, 'eyebrow')); ?></p>
        <h1><?php echo esc_html(ghalya_content_text($content, 'title')); ?></h1>
        <p><?php echo esc_html(ghalya_content_text($content, 'message')); ?></p>
        <div class="mt-handle"><?php echo esc_html(ghalya_content_text($content, 'social_handle')); ?></div>
        <a class="mt-btn-primary" href="<?php echo esc_url(ghalya_page_url('home')); ?>"><?php echo esc_html(ghalya_content_text($content, 'back_button')); ?></a>
      </div>
    </section>
  </div>
</main>
