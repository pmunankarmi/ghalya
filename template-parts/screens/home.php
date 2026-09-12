<?php
$content = isset($args['content']) && is_array($args['content']) ? $args['content'] : array();
$language = ghalya_current_language();
$profile_url = ghalya_page_url('profile', $language);
$tiers = ghalya_content_rows($content, 'tiers');
$benefit_icons = array('earn-icon.svg', 'grow-icon.svg', 'brand-partner-icon.svg', 'exclusive-product-icon.svg');
$deliverable_icons = array('store-icon.svg', 'reels-icon.svg', '2-story-icon.svg', 'fast-payout-icon.svg');
$partner_images = array('delsey-paris.png', 'kipling.png', 'danube.png', 'zahrat-alrawdah.png', 'bindawood.png');
$partner_slides = array_merge($partner_images, $partner_images);
?>
<main>
  <section class="mt-hero">
    <div class="container position-relative">
      <div class="mt-mobile-only" data-aos="fade-up">
        <h1 class="mt-mobile-title">
          <?php echo esc_html(ghalya_content_text($content, 'hero_title')); ?>
          <span class="mt-accent"><?php echo esc_html(ghalya_content_text($content, 'hero_accent')); ?></span>
        </h1>
        <div class="mt-mobile-creators mt-creators-row" role="img" aria-label="<?php echo esc_attr(ghalya_content_text($content, 'creators_alt')); ?>">
          <img src="<?php echo esc_url(ghalya_asset_url('images/hero-thumb-1.png')); ?>" alt="" />
          <img src="<?php echo esc_url(ghalya_asset_url('images/hero-thumb-2.png')); ?>" alt="" />
          <img src="<?php echo esc_url(ghalya_asset_url('images/hero-thumb-3.png')); ?>" alt="" />
        </div>
        <p class="mt-mobile-copy">
          <?php echo esc_html(ghalya_content_text($content, 'hero_intro')); ?>
          <strong><?php echo esc_html(ghalya_content_text($content, 'hero_intro_emphasis')); ?></strong>
        </p>
        <div class="mt-earn-note">
          <img class="mt-earn-icon" src="<?php echo esc_url(ghalya_asset_url('images/sparkles-icon.svg')); ?>" alt="" />
          <?php echo esc_html(ghalya_content_text($content, 'earn_note')); ?>
        </div>
        <h2 class="mt-mobile-reward-title"><?php echo esc_html(ghalya_content_text($content, 'reward_title')); ?></h2>
        <ul class="mt-tier-list" aria-label="<?php echo esc_attr(ghalya_content_text($content, 'tiers_label')); ?>">
          <?php foreach ($tiers as $tier) : ?>
            <li class="mt-tier-pill<?php echo !empty($tier['active']) ? ' mt-is-active' : ''; ?>"><?php echo esc_html($tier['label'] ?? ''); ?></li>
          <?php endforeach; ?>
        </ul>
        <div class="mt-mobile-reward">
          <strong><?php echo esc_html(ghalya_content_text($content, 'reward_amount')); ?></strong>
          <?php echo esc_html(ghalya_content_text($content, 'reward_suffix')); ?>
          <small><?php echo esc_html(ghalya_content_text($content, 'reward_detail')); ?></small>
        </div>
      </div>
      <a class="mt-btn-primary mt-community-join mt-mobile-join mt-mobile-only" href="<?php echo esc_url($profile_url); ?>">
        <?php echo esc_html(ghalya_content_text($content, 'join_button')); ?>
        <span class="mt-arrow-dot" aria-hidden="true">
          <img class="mt-up-arrow" src="<?php echo esc_url(ghalya_asset_url('images/up-arrow.svg')); ?>" alt="" />
        </span>
      </a>

      <div class="row align-items-center g-5 mt-desktop-hero">
        <div class="col-lg-7" data-aos="fade-up">
          <p class="mt-eyebrow"><?php echo esc_html(ghalya_content_text($content, 'hero_eyebrow')); ?></p>
          <h1 class="mt-hero-title">
            <?php echo esc_html(ghalya_content_text($content, 'hero_title')); ?>
            <span class="mt-accent"><?php echo esc_html(ghalya_content_text($content, 'hero_accent')); ?></span>
          </h1>
          <p class="mt-hero-copy">
            <?php echo esc_html(ghalya_content_text($content, 'hero_intro')); ?>
            <?php echo esc_html(ghalya_content_text($content, 'hero_intro_emphasis')); ?>
          </p>
          <div class="mt-earn-note">
            <img class="mt-earn-icon" src="<?php echo esc_url(ghalya_asset_url('images/sparkles-icon.svg')); ?>" alt="" />
            <?php echo esc_html(ghalya_content_text($content, 'earn_note')); ?>
          </div>
          <div class="d-flex flex-wrap gap-3 pt-4">
            <a class="mt-btn-primary mt-community-join" href="<?php echo esc_url($profile_url); ?>"><?php echo esc_html(ghalya_content_text($content, 'join_button')); ?></a>
            <a class="mt-btn-secondary" href="#benefits"><?php echo esc_html(ghalya_content_text($content, 'explore_button')); ?></a>
          </div>
        </div>
        <div class="col-lg-5" data-aos="fade-up" data-aos-delay="120">
          <div class="mt-hero-visual">
            <div class="mt-hero-image mt-creators-row" role="img" aria-label="<?php echo esc_attr(ghalya_content_text($content, 'creators_alt')); ?>">
              <img src="<?php echo esc_url(ghalya_asset_url('images/hero-thumb-1.png')); ?>" alt="" />
              <img src="<?php echo esc_url(ghalya_asset_url('images/hero-thumb-2.png')); ?>" alt="" />
              <img src="<?php echo esc_url(ghalya_asset_url('images/hero-thumb-3.png')); ?>" alt="" />
            </div>
            <ul class="mt-tier-list" aria-label="<?php echo esc_attr(ghalya_content_text($content, 'tiers_label')); ?>">
              <?php foreach ($tiers as $tier) : ?>
                <li class="mt-tier-pill<?php echo !empty($tier['active']) ? ' mt-is-active' : ''; ?>"><?php echo esc_html($tier['label'] ?? ''); ?></li>
              <?php endforeach; ?>
            </ul>
            <div class="mt-reward-panel">
              <div class="d-flex justify-content-between align-items-end gap-3">
                <div>
                  <div class="mt-reward-label"><?php echo esc_html(ghalya_content_text($content, 'reward_title')); ?></div>
                  <p class="mt-reward-value"><?php echo esc_html(ghalya_content_text($content, 'reward_amount')); ?></p>
                  <p class="mt-reward-small"><?php echo esc_html(ghalya_content_text($content, 'reward_desktop_suffix')); ?></p>
                </div>
                <span class="mt-arrow-dot"><img class="mt-reward-sparkle" src="<?php echo esc_url(ghalya_asset_url('images/sparkles-icon.svg')); ?>" alt="" /></span>
              </div>
              <p class="mt-reward-small pt-3"><?php echo esc_html(ghalya_content_text($content, 'reward_detail')); ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

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
                <?php if (isset($benefit_icons[$index])) : ?><img class="mt-feature-icon" src="<?php echo esc_url(ghalya_asset_url('images/' . $benefit_icons[$index])); ?>" alt="" /><?php endif; ?>
              </div>
              <h3><?php echo esc_html($benefit['title'] ?? ''); ?></h3>
              <p><?php echo esc_html($benefit['description'] ?? ''); ?></p>
            </article>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

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
                    <?php if (isset($deliverable_icons[$index])) : ?><img src="<?php echo esc_url(ghalya_asset_url('images/' . $deliverable_icons[$index])); ?>" alt="" /><?php endif; ?>
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

  <section class="mt-section mt-looking-section" aria-labelledby="looking-title">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-lg-6" data-aos="fade-up">
          <p class="mt-eyebrow"><?php echo esc_html(ghalya_content_text($content, 'looking_eyebrow')); ?></p>
          <h2 class="mt-section-title" id="looking-title"><?php echo esc_html(ghalya_content_text($content, 'looking_title')); ?></h2>
          <ul class="mt-check-list pt-4">
            <?php foreach (ghalya_content_rows($content, 'requirements') as $item) : ?><li><?php echo esc_html($item['text'] ?? ''); ?></li><?php endforeach; ?>
          </ul>
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

  <section class="mt-section mt-section-white" id="faq" aria-labelledby="faq-title">
    <div class="container">
      <div class="row g-5">
        <div class="col-lg-4" data-aos="fade-up">
          <p class="mt-eyebrow"><?php echo esc_html(ghalya_content_text($content, 'faq_eyebrow')); ?></p>
          <h2 class="mt-section-title" id="faq-title"><?php echo esc_html(ghalya_content_text($content, 'faq_title')); ?></h2>
          <?php if (ghalya_content_text($content, 'faq_intro') !== '') : ?><p class="mt-form-intro"><?php echo esc_html(ghalya_content_text($content, 'faq_intro')); ?></p><?php endif; ?>
        </div>
        <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
          <div class="accordion mt-faq" id="mtFaq-<?php echo esc_attr($language); ?>">
            <?php foreach (ghalya_content_rows($content, 'faqs') as $index => $faq) :
                $number = $index + 1;
                $heading_id = 'mtFaqHeading-' . $language . '-' . $number;
                $collapse_id = 'mtFaqCollapse-' . $language . '-' . $number;
                ?>
              <div class="accordion-item mt-faq-item">
                <h3 class="accordion-header" id="<?php echo esc_attr($heading_id); ?>">
                  <button class="accordion-button mt-faq-button<?php echo $index ? ' collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo esc_attr($collapse_id); ?>" aria-expanded="<?php echo $index ? 'false' : 'true'; ?>" aria-controls="<?php echo esc_attr($collapse_id); ?>">
                    <?php echo esc_html($faq['question'] ?? ''); ?>
                  </button>
                </h3>
                <div id="<?php echo esc_attr($collapse_id); ?>" class="accordion-collapse collapse<?php echo $index ? '' : ' show'; ?>" aria-labelledby="<?php echo esc_attr($heading_id); ?>" data-bs-parent="#mtFaq-<?php echo esc_attr($language); ?>">
                  <div class="accordion-body mt-faq-answer"><?php echo esc_html($faq['answer'] ?? ''); ?></div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
