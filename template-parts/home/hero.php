<?php
$content = isset($args['content']) && is_array($args['content']) ? $args['content'] : array();
$profile_url = isset($args['profile_url']) ? (string) $args['profile_url'] : '';
$tiers = ghalya_content_rows($content, 'tiers');
$active_tier_index = 0;

foreach ($tiers as $tier_index => $tier) {
    if (!empty($tier['active'])) {
        $active_tier_index = $tier_index;
        break;
    }
}
?>
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
        <div class="mt-tier-list" role="tablist" aria-label="<?php echo esc_attr(ghalya_content_text($content, 'tiers_label')); ?>">
          <?php foreach ($tiers as $tier_index => $tier) :
              $is_active = $tier_index === $active_tier_index;
              $reward_amount = !empty($tier['reward_amount']) ? (string) $tier['reward_amount'] : ghalya_content_text($content, 'reward_amount');
              ?>
            <button
              class="mt-tier-pill<?php echo $is_active ? ' active' : ''; ?>"
              id="mt-mobile-tier-tab-<?php echo esc_attr((string) $tier_index); ?>"
              type="button"
              role="tab"
              data-bs-toggle="tab"
              data-bs-target="#mt-mobile-tier-panel-<?php echo esc_attr((string) $tier_index); ?>"
              aria-controls="mt-mobile-tier-panel-<?php echo esc_attr((string) $tier_index); ?>"
              aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
              data-mt-tier-choice
              data-mt-tier-index="<?php echo esc_attr((string) $tier_index); ?>"
              data-mt-tier-label="<?php echo esc_attr((string) ($tier['label'] ?? '')); ?>"
              data-mt-tier-reward-amount="<?php echo esc_attr($reward_amount); ?>"
            ><?php echo esc_html($tier['label'] ?? ''); ?></button>
          <?php endforeach; ?>
        </div>
        <div class="tab-content mt-tier-panels">
          <?php foreach ($tiers as $tier_index => $tier) :
              $is_active = $tier_index === $active_tier_index;
              $reward_amount = !empty($tier['reward_amount']) ? (string) $tier['reward_amount'] : ghalya_content_text($content, 'reward_amount');
              ?>
            <div
              class="tab-pane fade<?php echo $is_active ? ' show active' : ''; ?>"
              id="mt-mobile-tier-panel-<?php echo esc_attr((string) $tier_index); ?>"
              role="tabpanel"
              aria-labelledby="mt-mobile-tier-tab-<?php echo esc_attr((string) $tier_index); ?>"
              tabindex="0"
            >
              <div class="mt-mobile-reward">
                <strong><?php echo esc_html($reward_amount); ?></strong>
                <?php echo esc_html(ghalya_content_text($content, 'reward_suffix')); ?>
                <small><?php echo esc_html(ghalya_content_text($content, 'reward_detail')); ?></small>
              </div>
            </div>
          <?php endforeach; ?>
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
          </div>
        </div>
        <div class="col-lg-5" data-aos="fade-up" data-aos-delay="120">
          <div class="mt-hero-visual">
            <div class="mt-hero-image mt-creators-row" role="img" aria-label="<?php echo esc_attr(ghalya_content_text($content, 'creators_alt')); ?>">
              <img src="<?php echo esc_url(ghalya_asset_url('images/hero-thumb-1.png')); ?>" alt="" />
              <img src="<?php echo esc_url(ghalya_asset_url('images/hero-thumb-2.png')); ?>" alt="" />
              <img src="<?php echo esc_url(ghalya_asset_url('images/hero-thumb-3.png')); ?>" alt="" />
            </div>
            <div class="mt-tier-list" role="tablist" aria-label="<?php echo esc_attr(ghalya_content_text($content, 'tiers_label')); ?>">
              <?php foreach ($tiers as $tier_index => $tier) :
                  $is_active = $tier_index === $active_tier_index;
                  $reward_amount = !empty($tier['reward_amount']) ? (string) $tier['reward_amount'] : ghalya_content_text($content, 'reward_amount');
                  ?>
                <button
                  class="mt-tier-pill<?php echo $is_active ? ' active' : ''; ?>"
                  id="mt-desktop-tier-tab-<?php echo esc_attr((string) $tier_index); ?>"
                  type="button"
                  role="tab"
                  data-bs-toggle="tab"
                  data-bs-target="#mt-desktop-tier-panel-<?php echo esc_attr((string) $tier_index); ?>"
                  aria-controls="mt-desktop-tier-panel-<?php echo esc_attr((string) $tier_index); ?>"
                  aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                  data-mt-tier-choice
                  data-mt-tier-index="<?php echo esc_attr((string) $tier_index); ?>"
                  data-mt-tier-label="<?php echo esc_attr((string) ($tier['label'] ?? '')); ?>"
                  data-mt-tier-reward-amount="<?php echo esc_attr($reward_amount); ?>"
                ><?php echo esc_html($tier['label'] ?? ''); ?></button>
              <?php endforeach; ?>
            </div>
            <div class="tab-content mt-tier-panels">
              <?php foreach ($tiers as $tier_index => $tier) :
                  $is_active = $tier_index === $active_tier_index;
                  $reward_amount = !empty($tier['reward_amount']) ? (string) $tier['reward_amount'] : ghalya_content_text($content, 'reward_amount');
                  ?>
                <div
                  class="tab-pane fade<?php echo $is_active ? ' show active' : ''; ?>"
                  id="mt-desktop-tier-panel-<?php echo esc_attr((string) $tier_index); ?>"
                  role="tabpanel"
                  aria-labelledby="mt-desktop-tier-tab-<?php echo esc_attr((string) $tier_index); ?>"
                  tabindex="0"
                >
                  <div class="mt-reward-panel">
                    <div class="d-flex justify-content-between align-items-end gap-3">
                      <div>
                        <div class="mt-reward-label"><?php echo esc_html(ghalya_content_text($content, 'reward_title')); ?></div>
                        <p class="mt-reward-value"><?php echo esc_html($reward_amount); ?></p>
                        <p class="mt-reward-small"><?php echo esc_html(ghalya_content_text($content, 'reward_desktop_suffix')); ?></p>
                      </div>
                      <span class="mt-arrow-dot"><img class="mt-reward-sparkle" src="<?php echo esc_url(ghalya_asset_url('images/sparkles-icon.svg')); ?>" alt="" /></span>
                    </div>
                    <p class="mt-reward-small pt-3"><?php echo esc_html(ghalya_content_text($content, 'reward_detail')); ?></p>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
