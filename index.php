<?php
/**
 * Last one standing index.php template file
 *
 * Used to display when there is no other template file
 * on your WordPress site.
 *
 */
?>
<?php get_header(); ?>

<?php
if (have_posts()) :
   while (have_posts()) :
?>

<h2 id="post-<?php the_ID(); ?>">
<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>">

<?php
      the_post();
      the_content();
   endwhile;
endif;

//comments_template();
?>

<?php get_footer(); ?>