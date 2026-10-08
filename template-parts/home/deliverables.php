<?php
$content = isset($args['content']) && is_array($args['content']) ? $args['content'] : array();
$deliverable_icons = array('store-icon.svg', 'reels-icon.svg', '2-story-icon.svg', 'fast-payout-icon.svg');
?>
<section class="mt-section mt-section-white mt-deliver-section" aria-labelledby="deliver-title">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-lg-4" data-aos="fade-up">
          <p class="mt-eyebrow"><?php echo esc_html(ghalya_content_text($content, 'deliver_eyebrow')); ?></p>
          <h2 class="mt-section-title" id="deliver-title"><?php echo esc_html(ghalya_content_text($content, 'deliver_title')); ?></h2>
        </div>
        <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
          <div class="row g-3">
            <?php foreach (ghalya_content_rows($content, 'deliverables') as $index => $deliverable) : ?>
              <div class="col-sm-6">
                <article class="mt-card mt-deliver-card">
                  <div class="mt-deliver-icon-shell" aria-hidden="true">
                    <?php if (!empty($deliverable['icon'])) : ?>
                      <?php echo wp_get_attachment_image(absint($deliverable['icon']), 'thumbnail', false, array('alt' => '')); ?>
                    <?php elseif (isset($deliverable_icons[$index])) : ?>
                      <img src="<?php echo esc_url(ghalya_asset_url('images/' . $deliverable_icons[$index])); ?>" alt="" />
                    <?php endif; ?>
                  </div>
                  <h3><?php echo esc_html($deliverable['title'] ?? ''); ?></h3>
                  <p><?php echo esc_html($deliverable['description'] ?? ''); ?></p>
                </article>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
