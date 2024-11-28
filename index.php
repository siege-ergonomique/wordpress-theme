<?php get_header(); ?>

<main id="main" role="main" id="post-<?php the_ID(); ?>">

    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php the_content(); ?>
        <?php endwhile; ?>
    <?php endif; ?>

</main>

<?php get_footer(); ?>