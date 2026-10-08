<?php
$content = isset($args['content']) && is_array($args['content']) ? $args['content'] : array();
$partner_logos = ghalya_content_rows($content, 'partner_logos');

if (!$partner_logos) {
    $partner_logos = array(
        array('file' => 'delsey-paris.png', 'name' => 'Delsey Paris'),
        array('file' => 'kipling.png', 'name' => 'Kipling'),
        array('file' => 'danube.png', 'name' => 'Danube'),
        array('file' => 'zahrat-alrawdah.png', 'name' => 'Zahrat Al Rawdah'),
        array('file' => 'bindawood.png', 'name' => 'BinDawood'),
    );
}

$partner_slides = array_merge($partner_logos, $partner_logos);
?>
<section class="mt-section mt-section-white mt-partners-section" aria-labelledby="partners-title">
    <div class="container">
      <div class="text-center mb-5" data-aos="fade-up">
        <p class="mt-eyebrow"><?php echo esc_html(ghalya_content_text($content, 'partners_eyebrow')); ?></p>
        <h2 class="mt-section-title" id="partners-title"><?php echo esc_html(ghalya_content_text($content, 'partners_title')); ?></h2>
        <div class="mt-mobile-universe" aria-hidden="true">
          <span><?php echo esc_html(ghalya_content_text($content, 'partners_mobile_prefix')); ?></span>
          <img src="<?php echo esc_url(ghalya_asset_url('images/ghalya-logo.png')); ?>" alt="" />
          <span><?php echo esc_html(ghalya_content_text($content, 'partners_mobile_suffix')); ?></span>
        </div>
        <p class="mt-mobile-universe-copy"><?php echo esc_html(ghalya_content_text($content, 'partners_mobile_copy')); ?></p>
      </div>
      <div class="swiper mt-partner-swiper" data-aos="fade-up" data-aos-delay="100" aria-label="<?php echo esc_attr(ghalya_content_text($content, 'partners_label')); ?>">
        <div class="swiper-wrapper">
          <?php foreach ($partner_slides as $partner_logo) : ?>
            <div class="swiper-slide">
              <div class="mt-partner">
                <?php if (!empty($partner_logo['logo'])) : ?>
                  <?php echo wp_get_attachment_image(absint($partner_logo['logo']), 'medium', false, array('alt' => (string) ($partner_logo['name'] ?? ''))); ?>
                <?php elseif (!empty($partner_logo['file'])) : ?>
                  <img src="<?php echo esc_url(ghalya_asset_url('images/' . $partner_logo['file'])); ?>" alt="<?php echo esc_attr((string) ($partner_logo['name'] ?? '')); ?>" />
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
