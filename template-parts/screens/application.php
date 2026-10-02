<?php
$screen = isset($args['screen']) ? $args['screen'] : 'profile';
$content = isset($args['content']) && is_array($args['content']) ? $args['content'] : array();
$selected_tier = isset($args['selected_tier']) && is_array($args['selected_tier']) ? $args['selected_tier'] : array();
$selected_tier_index = isset($args['selected_tier_index']) ? absint($args['selected_tier_index']) : 0;
$tiers = isset($args['tiers']) && is_array($args['tiers']) ? $args['tiers'] : array();
$language = ghalya_current_language();
$steps = array('profile', 'tier', 'work', 'proposal', 'contact');
$step = array_search($screen, $steps, true);
$step = $step === false ? 0 : $step;
$previous_screen = $step === 0 ? 'home' : $steps[$step - 1];
$next_screen = $step < 4 ? $steps[$step + 1] : 'success';
$previous_url = ghalya_page_url($previous_screen, $language);
$next_url = ghalya_page_url($next_screen, $language);
$is_contact = $screen === 'contact';
$join_content = ghalya_join_content($language);
$progress_pages = ghalya_application_pages($language);
$progress_page_ids = wp_list_pluck($progress_pages, 'ID');
$progress_step = array_search(get_queried_object_id(), $progress_page_ids, true);
$progress_step = $progress_step === false ? $step : $progress_step;
$total_steps = $progress_pages ? count($progress_pages) : count($steps);
$step_label = sprintf(__('Step %1$d of %2$d', 'ghalya'), $progress_step + 1, $total_steps);
$tier_label = isset($selected_tier['label']) ? trim((string) $selected_tier['label']) : '';
$creator_label = ghalya_content_text($content, 'creator_label');
$tier_name = ghalya_content_text($content, 'tier_name');
$tier_amount = !empty($selected_tier['reward_amount']) ? (string) $selected_tier['reward_amount'] : ghalya_content_text($content, 'tier_amount');

if ($tier_label !== '') {
    $tier_name = $language === 'ar' ? trim($creator_label . ' ' . $tier_label) : trim($tier_label . ' ' . $creator_label);
}
?>
<main class="mt-form-page">
  <?php if ($is_contact && !empty($_GET['submission_error'])) : ?>
    <div class="container">
      <div class="mt-form-card mt-submission-notice" role="alert">
        <?php echo esc_html(ghalya_submission_error_message(sanitize_key(wp_unslash($_GET['submission_error'])))); ?>
      </div>
    </div>
  <?php endif; ?>
  <div class="container">
    <div class="mt-form-layout">
      <aside class="mt-form-aside" data-aos="fade-up">
        <h2><?php echo esc_html(ghalya_content_text($join_content, 'aside_title')); ?></h2>
        <p><?php echo esc_html(ghalya_content_text($join_content, 'aside_intro')); ?></p>
        <ol class="mt-progress-list">
          <?php foreach ($progress_pages as $index => $progress_page) :
              $state_class = $index < $progress_step ? ' mt-is-done' : ($index === $progress_step ? ' mt-is-active' : '');
              ?>
            <li class="mt-progress-item<?php echo esc_attr($state_class); ?>">
              <span class="mt-progress-number"><?php echo $index < $progress_step ? '&#10003;' : esc_html((string) ($index + 1)); ?></span>
              <span><?php echo esc_html(get_the_title($progress_page)); ?></span>
            </li>
          <?php endforeach; ?>
        </ol>
      </aside>

      <form
        action="<?php echo esc_url($is_contact ? admin_url('admin-post.php') : $next_url); ?>"
        class="mt-form-card mt-js-form"
        data-aos="fade-up"
        data-aos-delay="80"
        <?php if ($screen === 'tier') : ?>data-mt-tier-options="<?php echo esc_attr(wp_json_encode($tiers)); ?>" data-mt-assigned-tier-index="<?php echo esc_attr((string) $selected_tier_index); ?>" data-mt-creator-label="<?php echo esc_attr($creator_label); ?>"<?php endif; ?>
        <?php if ($is_contact) : ?>data-mt-sendmail<?php else : ?>data-mt-next="<?php echo esc_url($next_url); ?>"<?php endif; ?>
        method="<?php echo $is_contact ? 'post' : 'get'; ?>"
      >
        <?php if ($is_contact) : ?>
          <input name="action" type="hidden" value="ghalya_submit_application" />
          <?php wp_nonce_field('ghalya_submit_application', '_ghalya_nonce'); ?>
          <input name="language" type="hidden" value="<?php echo esc_attr($language); ?>" />
          <input name="application_data" type="hidden" value="" />
        <?php endif; ?>

        <div class="mt-form-top">
          <a class="mt-back-link" href="<?php echo esc_url($previous_url); ?>"><?php echo esc_html(ghalya_application_label('previous')); ?></a>
          <span class="mt-step-label"><?php echo esc_html($step_label); ?></span>
        </div>
        <h1 class="mt-form-title"><?php echo esc_html(get_the_title(get_queried_object_id())); ?></h1>
        <p class="mt-form-intro mt-form-lead"><?php echo esc_html(ghalya_content_text($content, 'intro')); ?></p>

        <?php if ($screen === 'profile') : ?>
          <div class="row g-3">
            <div class="col-md-6">
              <div class="mt-field">
                <label class="mt-label" for="full-name"><?php echo esc_html(ghalya_content_text($content, 'full_name_label')); ?></label>
                <input class="mt-input" id="full-name" name="full_name" required type="text" placeholder="<?php echo esc_attr(ghalya_content_text($content, 'full_name_placeholder')); ?>" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="mt-field">
                <label class="mt-label" for="instagram"><?php echo esc_html(ghalya_content_text($content, 'instagram_label')); ?></label>
                <input class="mt-input" id="instagram" name="instagram" required type="text" placeholder="<?php echo esc_attr(ghalya_content_text($content, 'instagram_placeholder')); ?>" />
              </div>
            </div>
          </div>
          <div class="mt-field">
            <span class="mt-label"><?php echo esc_html(ghalya_content_text($content, 'followers_label')); ?></span>
            <div class="mt-chip-row">
              <?php foreach (ghalya_content_rows($content, 'followers_choices') as $index => $choice) : $id = 'followers-' . ($index + 1); ?>
                <input class="mt-choice-input" id="<?php echo esc_attr($id); ?>" name="followers"<?php echo $index === 0 ? ' required' : ''; ?> type="radio" value="<?php echo esc_attr($choice['value'] ?? ''); ?>" />
                <label class="mt-choice" for="<?php echo esc_attr($id); ?>"><?php echo esc_html($choice['label'] ?? ''); ?></label>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="mt-field">
            <label class="mt-label" for="city"><?php echo esc_html(ghalya_content_text($content, 'city_label')); ?></label>
            <select class="mt-select" id="city" name="city" required>
              <option value="" selected disabled><?php echo esc_html(ghalya_content_text($content, 'city_placeholder')); ?></option>
              <?php foreach (ghalya_content_rows($content, 'city_choices') as $choice) : ?>
                <option value="<?php echo esc_attr($choice['value'] ?? ''); ?>"><?php echo esc_html($choice['label'] ?? ''); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mt-field">
            <span class="mt-label"><?php echo esc_html(ghalya_content_text($content, 'category_label')); ?></span>
            <div class="mt-chip-row">
              <?php foreach (ghalya_content_rows($content, 'category_choices') as $index => $choice) : $id = 'category-' . ($index + 1); ?>
                <input class="mt-choice-input" id="<?php echo esc_attr($id); ?>" name="content_categories"<?php echo $index === 0 ? ' required' : ''; ?> type="checkbox" value="<?php echo esc_attr($choice['value'] ?? ''); ?>" />
                <label class="mt-choice" for="<?php echo esc_attr($id); ?>"><?php echo esc_html($choice['label'] ?? ''); ?></label>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

        <?php if ($screen === 'tier') : ?>
          <input data-mt-tier-value name="assigned_tier" type="hidden" value="<?php echo esc_attr($tier_label); ?>" />
          <input data-mt-tier-amount-value name="tier_amount" type="hidden" value="<?php echo esc_attr($tier_amount); ?>" />
          <div class="mt-tier-name" data-mt-tier-name><?php echo esc_html($tier_name); ?></div>
          <div class="mt-tier-card">
            <div class="mt-tier-amount">
              <strong data-mt-tier-amount><?php echo esc_html($tier_amount); ?></strong>
              <span><?php echo esc_html(ghalya_content_text($content, 'tier_suffix')); ?></span>
            </div>
          </div>
          <div class="mt-tier-deliverables">
            <span class="mt-label"><?php echo esc_html(ghalya_content_text($content, 'deliverables_label')); ?></span>
            <div class="mt-mini-deliverables">
              <?php foreach (ghalya_content_rows($content, 'deliverables') as $deliverable) : ?>
                <div class="mt-mini-deliverable"><?php echo esc_html($deliverable['text'] ?? ''); ?></div>
              <?php endforeach; ?>
            </div>
          </div>
          <p class="mt-tier-note"><?php echo esc_html(ghalya_content_text($content, 'note')); ?></p>
        <?php endif; ?>

        <?php if ($screen === 'work') : ?>
          <?php
          $work_fields = array(
              array('id' => 'instagram-link', 'name' => 'instagram_url', 'type' => 'url', 'content_key' => 'instagram', 'required' => true),
              array('id' => 'tiktok-link', 'name' => 'tiktok_url', 'type' => 'url', 'content_key' => 'tiktok', 'required' => false),
              array('id' => 'snapchat', 'name' => 'snapchat', 'type' => 'text', 'content_key' => 'snapchat', 'required' => false),
              array('id' => 'brand-content', 'name' => 'brand_content_url', 'type' => 'url', 'content_key' => 'brand_content', 'required' => false),
          );
          ?>
          <div class="row g-3">
            <?php foreach ($work_fields as $field) : ?>
              <div class="col-md-6">
                <div class="mt-field">
                  <label class="mt-label" for="<?php echo esc_attr($field['id']); ?>"><?php echo esc_html(ghalya_content_text($content, $field['content_key'] . '_label')); ?></label>
                  <input class="mt-input" id="<?php echo esc_attr($field['id']); ?>" name="<?php echo esc_attr($field['name']); ?>"<?php echo $field['required'] ? ' required' : ''; ?> type="<?php echo esc_attr($field['type']); ?>" placeholder="<?php echo esc_attr(ghalya_content_text($content, $field['content_key'] . '_placeholder')); ?>" />
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="mt-extra-links" id="additional-work-links" data-mt-extra-links aria-live="polite"></div>
          <button class="mt-back-link mt-add-link" type="button" data-mt-add-link aria-controls="additional-work-links" data-mt-label="<?php echo esc_attr(ghalya_content_text($content, 'brand_content_label')); ?>" data-mt-placeholder="<?php echo esc_attr(ghalya_content_text($content, 'brand_content_placeholder')); ?>">
            <?php echo esc_html(ghalya_content_text($content, 'add_link_button')); ?>
          </button>
        <?php endif; ?>

        <?php if ($screen === 'proposal') : ?>
          <div class="mt-field">
            <span class="mt-label"><?php echo esc_html(ghalya_content_text($content, 'brands_label')); ?></span>
            <div class="mt-chip-row">
              <?php foreach (ghalya_content_rows($content, 'brand_choices') as $index => $choice) : $id = 'brand-' . ($index + 1); ?>
                <input class="mt-choice-input" id="<?php echo esc_attr($id); ?>" name="brands"<?php echo $index === 0 ? ' required' : ''; ?> type="checkbox" value="<?php echo esc_attr($choice['value'] ?? ''); ?>" />
                <label class="mt-choice" for="<?php echo esc_attr($id); ?>"><?php echo esc_html($choice['label'] ?? ''); ?></label>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="mt-field mt-4">
            <label class="mt-label" for="availability"><?php echo esc_html(ghalya_content_text($content, 'availability_label')); ?></label>
            <input class="mt-input" id="availability" min="<?php echo esc_attr(current_time('Y-m-d')); ?>" name="availability" required type="date" />
          </div>
        <?php endif; ?>

        <?php if ($screen === 'contact') : ?>
          <div class="row g-3">
            <div class="col-md-6">
              <div class="mt-field">
                <label class="mt-label" for="email"><?php echo esc_html(ghalya_content_text($content, 'email_label')); ?></label>
                <input class="mt-input" id="email" name="email" required type="email" placeholder="<?php echo esc_attr(ghalya_content_text($content, 'email_placeholder')); ?>" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="mt-field">
                <label class="mt-label" for="phone"><?php echo esc_html(ghalya_content_text($content, 'phone_label')); ?></label>
                <input class="mt-input" id="phone" minlength="8" name="phone" required type="tel" placeholder="<?php echo esc_attr(ghalya_content_text($content, 'phone_placeholder')); ?>" />
              </div>
            </div>
          </div>
          <label class="mt-agree mt-4">
            <input name="terms" required type="checkbox" />
            <span>
              <?php echo esc_html(ghalya_content_text($content, 'consent_before')); ?>
              <a href="<?php echo esc_url(ghalya_page_url('terms', $language)); ?>"><?php echo esc_html(ghalya_content_text($content, 'consent_link')); ?></a>
              <?php echo esc_html(ghalya_content_text($content, 'consent_after')); ?>
            </span>
          </label>
        <?php endif; ?>

        <div class="mt-form-actions">
          <a class="mt-btn-secondary" href="<?php echo esc_url($previous_url); ?>"><?php echo esc_html(ghalya_application_label('back')); ?></a>
          <button class="mt-btn-primary" type="submit"><?php echo esc_html(ghalya_application_label($is_contact ? 'submit' : 'continue')); ?></button>
        </div>
      </form>
    </div>
  </div>
</main>
