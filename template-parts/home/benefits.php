<?php
$content = isset($args['content']) && is_array($args['content']) ? $args['content'] : array();
$benefit_icons = array('earn-icon.svg', 'grow-icon.svg', 'brand-partner-icon.svg', 'exclusive-product-icon.svg');
?>
<section class="mt-section" id="benefits" aria-labelledby="benefits-title">
    <div class="container">
      <div class="text-center mb-5" data-aos="fade-up">
        <p class="mt-eyebrow"><?php echo esc_html(ghalya_content_text($content, 'benefits_eyebrow')); ?></p>
        <h2 class="mt-section-title" id="benefits-title"><?php echo esc_html(ghalya_content_text($content, 'benefits_title')); ?></h2>
        <?php if (ghalya_content_text($content, 'benefits_intro') !== '') : ?>
          <p class="mt-section-copy"><?php echo esc_html(ghalya_content_text($content, 'benefits_intro')); ?></p>
        <?php endif; ?>
      </div>
      <div class="row g-4">
        <?php foreach (ghalya_content_rows($content, 'benefits') as $index => $benefit) : ?>
          <div class="col-md-6 col-xl-3">
            <article class="mt-card" data-aos="fade-up"<?php echo $index ? ' data-aos-delay="' . esc_attr((string) ($index * 80)) . '"' : ''; ?>>
              <div class="mt-icon-shell" aria-hidden="true">
                <?php if (!empty($benefit['icon'])) : ?>
                  <?php echo wp_get_attachment_image(absint($benefit['icon']), 'thumbnail', false, array('class' => 'mt-feature-icon', 'alt' => '')); ?>
                <?php elseif (isset($benefit_icons[$index])) : ?>
                  <img class="mt-feature-icon" src="<?php echo esc_url(ghalya_asset_url('images/' . $benefit_icons[$index])); ?>" alt="" />
                <?php endif; ?>
              </div>
              <h3><?php echo esc_html($benefit['title'] ?? ''); ?></h3>
              <p><?php echo esc_html($benefit['description'] ?? ''); ?></p>
            </article>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
