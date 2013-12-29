<?php get_header("breadcrumb"); ?>

<?php
    if (have_posts()) :
       while (have_posts()) :
          the_post();
          the_content();
?>

<?php

       endwhile;
    endif;
?>

<?php get_footer(); ?>
