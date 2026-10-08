<?php
$content = isset($args['content']) && is_array($args['content']) ? $args['content'] : array();
$language = isset($args['language']) ? (string) $args['language'] : ghalya_current_language();
$terms_url = isset($args['terms_url']) ? (string) $args['terms_url'] : '';
$faq_title = ghalya_content_text($content, 'faq_title');
?>
<section class="mt-section mt-section-white" id="faq" aria-labelledby="faq-title">
    <div class="container">
      <div class="row g-5">
        <div class="col-lg-4" data-aos="fade-up">
          <p class="mt-eyebrow"><?php echo esc_html(ghalya_content_text($content, 'faq_eyebrow')); ?></p>
          <?php if ($faq_title !== '') : ?>
            <h2 class="mt-section-title" id="faq-title"><?php echo esc_html($faq_title); ?></h2>
          <?php else : ?>
            <h2 class="visually-hidden" id="faq-title"><?php echo esc_html(ghalya_content_text($content, 'faq_eyebrow')); ?></h2>
          <?php endif; ?>
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
                  <div class="accordion-body mt-faq-answer">
                    <?php echo esc_html($faq['answer'] ?? ''); ?>
                    <?php if ($terms_url !== '' && !empty($faq['terms_link_label'])) : ?>
                      <a href="<?php echo esc_url($terms_url); ?>"><?php echo esc_html($faq['terms_link_label']); ?></a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
