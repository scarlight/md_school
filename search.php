<?php
/**
 * Custom search.php template file
 *
 * Used to display a search function on the webpage
 * WordPress site.
 *
 */
?>
<?php get_header(); ?>

<?php
    if (have_posts()) :
		while (have_posts()) :
			$postid = get_the_ID();
			echo $postid;
			echo "<h1> start::get_all_page_ids():: </h1>";
			echo "<pre>";
			    print_r(get_all_page_ids());
			echo "</pre>";
			echo "<h1> end::get_all_page_ids():: </h1>";
			the_post();
			the_content();
		echo "--------------------------------------------------------------------------------------------------------------------------------------";
		endwhile;
    endif;
?>

<?php get_footer(); ?>