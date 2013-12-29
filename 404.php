<?php
/**
 * Custom 404.php template file
 *
 * Used to display if there is a 404 error on the webpage
 * WordPress site.
 *
 */
?>

<?php get_header("breadcrumb"); ?>

<span class="four-o-four">404 means not found!</span>
<br>
<p class="four-o-four-desc">We're sorry, we don't have that USB design here! Try our search box instead to find what you are looking for.</p>

<?php locate_template( 'searchform-404.php', true, true); ?>

<?php get_footer(); ?>