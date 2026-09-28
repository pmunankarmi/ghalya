<?php
$content = isset($args['content']) && is_array($args['content']) ? $args['content'] : array();
$partner_images = array('delsey-paris.png', 'kipling.png', 'danube.png', 'zahrat-alrawdah.png', 'bindawood.png');
$partner_slides = array_merge($partner_images, $partner_images);
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
          <?php foreach ($partner_slides as $partner_image) : ?>
            <div class="swiper-slide"><div class="mt-partner"><img src="<?php echo esc_url(ghalya_asset_url('images/' . $partner_image)); ?>" alt="" /></div></div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
