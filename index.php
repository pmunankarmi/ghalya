<?php

get_header();
?>
<main class="mt-form-page">
  <div class="container">
    <?php if (have_posts()) : ?>
      <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class('mt-form-card'); ?>>
          <h1 class="mt-form-title"><?php the_title(); ?></h1>
          <?php the_content(); ?>
        </article>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>
</main>
<?php
get_footer();

