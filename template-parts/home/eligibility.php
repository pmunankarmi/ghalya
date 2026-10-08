<?php
$content = isset($args['content']) && is_array($args['content']) ? $args['content'] : array();
$profile_url = isset($args['profile_url']) ? (string) $args['profile_url'] : '';
$terms_url = isset($args['terms_url']) ? (string) $args['terms_url'] : '';
?>
<section class="mt-section mt-looking-section" aria-labelledby="looking-title">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-lg-6" data-aos="fade-up">
          <p class="mt-eyebrow"><?php echo esc_html(ghalya_content_text($content, 'looking_eyebrow')); ?></p>
          <h2 class="mt-section-title" id="looking-title"><?php echo esc_html(ghalya_content_text($content, 'looking_title')); ?></h2>
          <ul class="mt-check-list pt-4">
            <?php foreach (ghalya_content_rows($content, 'requirements') as $item) : ?><li><?php echo esc_html($item['text'] ?? ''); ?></li><?php endforeach; ?>
          </ul>
          <?php if (ghalya_content_text($content, 'eligibility_terms_title') !== '' || ghalya_content_text($content, 'eligibility_terms_text') !== '') : ?>
            <div class="mt-eligibility-terms">
              <?php if (ghalya_content_text($content, 'eligibility_terms_title') !== '') : ?>
                <h3><?php echo esc_html(ghalya_content_text($content, 'eligibility_terms_title')); ?></h3>
              <?php endif; ?>
              <?php if (ghalya_content_text($content, 'eligibility_terms_text') !== '') : ?>
                <p><?php echo esc_html(ghalya_content_text($content, 'eligibility_terms_text')); ?></p>
              <?php endif; ?>
              <?php if ($terms_url !== '' && ghalya_content_text($content, 'eligibility_terms_link_label') !== '') : ?>
                <a href="<?php echo esc_url($terms_url); ?>"><?php echo esc_html(ghalya_content_text($content, 'eligibility_terms_link_label')); ?></a>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
          <div class="mt-card">
            <h3 class="mb-3"><?php echo esc_html(ghalya_content_text($content, 'categories_title')); ?></h3>
            <div class="mt-chip-row">
              <?php foreach (ghalya_content_rows($content, 'categories') as $index => $item) : ?>
                <span class="mt-chip"><?php echo esc_html($item['text'] ?? ''); ?></span>
                <?php if ($index === 3) : ?><span class="mt-chip-row-break" aria-hidden="true"></span><?php endif; ?>
              <?php endforeach; ?>
            </div>
            <a class="mt-btn-primary mt-4" href="<?php echo esc_url($profile_url); ?>"><?php echo esc_html(ghalya_content_text($content, 'start_button')); ?></a>
          </div>
        </div>
      </div>
    </div>
  </section>
