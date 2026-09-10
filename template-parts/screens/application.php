<?php
$screen = isset($args['screen']) ? $args['screen'] : 'profile';
$content = isset($args['content']) && is_array($args['content']) ? $args['content'] : array();
$language = ghalya_current_language();
$steps = array('profile', 'tier', 'work', 'proposal', 'contact');
$step = array_search($screen, $steps, true);
$step = $step === false ? 0 : $step;
$previous_screen = $step === 0 ? 'home' : $steps[$step - 1];
$next_screen = $step < 4 ? $steps[$step + 1] : 'success';
$previous_url = ghalya_page_url($previous_screen, $language);
$next_url = ghalya_page_url($next_screen, $language);
$is_contact = $screen === 'contact';
$progress_labels = ghalya_content_rows($content, 'progress_labels');
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
        <h2><?php echo esc_html(ghalya_content_text($content, 'aside_title')); ?></h2>
        <p><?php echo esc_html(ghalya_content_text($content, 'aside_intro')); ?></p>
        <ol class="mt-progress-list">
          <?php foreach ($steps as $index => $progress_screen) :
              $label = isset($progress_labels[$index]['label']) ? $progress_labels[$index]['label'] : '';
              $state_class = $index < $step ? ' mt-is-done' : ($index === $step ? ' mt-is-active' : '');
              ?>
            <li class="mt-progress-item<?php echo esc_attr($state_class); ?>">
              <span class="mt-progress-number"><?php echo $index < $step ? '&#10003;' : esc_html((string) ($index + 1)); ?></span>
              <span><?php echo esc_html($label); ?></span>
            </li>
          <?php endforeach; ?>
        </ol>
      </aside>

      <form
        action="<?php echo esc_url($is_contact ? admin_url('admin-post.php') : $next_url); ?>"
        class="mt-form-card mt-js-form"
        data-aos="fade-up"
        data-aos-delay="80"
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
          <a class="mt-back-link" href="<?php echo esc_url($previous_url); ?>"><?php echo esc_html(ghalya_content_text($content, 'top_back_label')); ?></a>
          <span class="mt-step-label"><?php echo esc_html(ghalya_content_text($content, 'step_label')); ?></span>
        </div>
        <h1 class="mt-form-title"><?php echo esc_html(ghalya_content_text($content, 'title')); ?></h1>
        <p class="mt-form-intro"><?php echo esc_html(ghalya_content_text($content, 'intro')); ?></p>

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
          <div class="mt-tier-card">
            <div class="mt-tier-name"><?php echo esc_html(ghalya_content_text($content, 'tier_name')); ?></div>
            <div class="mt-tier-amount">
              <?php echo esc_html(ghalya_content_text($content, 'tier_amount')); ?>
              <span><?php echo esc_html(ghalya_content_text($content, 'tier_suffix')); ?></span>
            </div>
            <hr />
            <span class="mt-label"><?php echo esc_html(ghalya_content_text($content, 'deliverables_label')); ?></span>
            <div class="mt-mini-deliverables">
              <?php foreach (ghalya_content_rows($content, 'deliverables') as $deliverable) : ?>
                <div class="mt-mini-deliverable"><?php echo esc_html($deliverable['text'] ?? ''); ?></div>
              <?php endforeach; ?>
            </div>
          </div>
          <p class="mt-form-intro mt-4"><?php echo esc_html(ghalya_content_text($content, 'note')); ?></p>
        <?php endif; ?>

        <?php if ($screen === 'work') : ?>
          <?php
          $work_fields = array(
              array('instagram-link', 'instagram_url', 'url', 'instagram'),
              array('tiktok-link', 'tiktok_url', 'url', 'tiktok'),
              array('snapchat', 'snapchat', 'text', 'snapchat'),
              array('brand-content', 'brand_content_url', 'url', 'brand_content'),
          );
          ?>
          <div class="row g-3">
            <?php foreach ($work_fields as $field) : ?>
              <div class="col-md-6">
                <div class="mt-field">
                  <label class="mt-label" for="<?php echo esc_attr($field[0]); ?>"><?php echo esc_html(ghalya_content_text($content, $field[3] . '_label')); ?></label>
                  <input class="mt-input" id="<?php echo esc_attr($field[0]); ?>" name="<?php echo esc_attr($field[1]); ?>" required type="<?php echo esc_attr($field[2]); ?>" placeholder="<?php echo esc_attr(ghalya_content_text($content, $field[3] . '_placeholder')); ?>" />
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
            <input class="mt-input" id="availability" name="availability" required type="date" />
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
          <a class="mt-btn-secondary" href="<?php echo esc_url($previous_url); ?>"><?php echo esc_html(ghalya_content_text($content, 'back_button')); ?></a>
          <button class="mt-btn-primary" type="submit"><?php echo esc_html(ghalya_content_text($content, $is_contact ? 'submit_button' : 'next_button')); ?></button>
        </div>
      </form>
    </div>
  </div>
</main>
