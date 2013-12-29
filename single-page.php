<?php get_header("breadcrumb"); ?>

<?php
    if (have_posts()) :
       while (have_posts()) :
          the_post();
          the_content();
?>

<a class="postThumb" href="<?php the_permalink() ?>">
<?php echo get_post_meta($post->ID, 'random_pie', true); ?>
<?php echo "-----------------------"; ?>
<?php the_ID(); ?>
</a>

<?php

       endwhile;
    endif;
?>

<?php get_footer(); ?>
