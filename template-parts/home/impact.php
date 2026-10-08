<?php
$content = isset($args['content']) && is_array($args['content']) ? $args['content'] : array();
$stats = ghalya_content_rows($content, 'impact_stats');
if (!$stats) {
    return;
}
?>
<section class="mt-section mt-section-white" aria-labelledby="impact-title">
  <div class="container">
    <h2 class="mt-section-title text-center mb-5" id="impact-title"><?php echo esc_html(ghalya_content_text($content, 'impact_title')); ?></h2>
    <div class="row g-4">
      <?php foreach ($stats as $stat) :
          $value = trim((string) ($stat['value'] ?? ''));
          ?>
        <div class="col-md-4">
          <div class="mt-card mt-impact-card h-100 text-center">
            <p
              class="mt-impact-value"
              data-mt-counter
              data-mt-counter-value="<?php echo esc_attr($value); ?>"
              aria-label="<?php echo esc_attr($value); ?>"
              aria-live="off"
            ><?php echo esc_html($value); ?></p>
            <p class="mt-impact-label"><?php echo esc_html($stat['label'] ?? ''); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
