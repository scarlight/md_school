<?php
/**
 * Custom search.php template file
 *
 * Used to display a search function on the webpage
 * WordPress site.
 *
 */
?>
<?php get_header("breadcrumb"); ?>

<div id="primary" class="content-area">
	<div id="content" class="site-content" role="main">

	<?php if ( have_posts() ) : ?>

		<?php
			$search_count = 0;
	
			$search = new WP_Query("s=$s & showposts=-1");

			if($search->have_posts()) : 
				while($search->have_posts()) : $search->the_post();	
					$search_count++;
				endwhile;
			endif;

		?>

		<p class="search-result">SEARCH RESULT:</p>

		<h2 class="pagetitle">You searched for <span class="orange-text">"<?php the_search_query() ?>"</span> and we found <i><?php echo $search_count; ?></i> results.</h2>

		<?php /* The loop */ ?>

		<ol type="1" class="search-list">

		<?php while ( have_posts() ) : the_post(); ?>

			<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				

				
				<?php if ( is_search() ) : // Only display Excerpts for Search ?>
				<h5>
					<a class="orange-text" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>

					<?php if( !get_the_excerpt() ) : ?>
						
						<i style="font-size:13px; font-weight:normal; margin-left:10px;"><a href="<?php the_permalink(); ?>"> ...read more</a></i>
						
					<?php endif; ?>
				</h5>

				<?php if( get_the_excerpt() ) : ?>
					
					<blockquote class="entry-summary">
						<?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
						<div class="entry-thumbnail">
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail(); ?>
							</a>
						</div>
						<?php endif; ?>

						<?php the_excerpt(); ?>
						
						<i><a href="<?php the_permalink(); ?>">...read more</a></i>

						<div class="clear"></div>

					</blockquote>
					
				<?php endif; ?>
				
				<?php else : ?>
				<div class="entry-content">
					<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'massdata' ) ); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'massdata' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
				</div><!-- .entry-content -->

				<?php endif; ?>

			<?php 
				// output sample
				if(0){
					echo "<pre>";
					echo 'the_ID() : '.the_ID();
					echo "</pre>";

					echo "<pre>";
					echo 'the_permalink(): '.the_permalink();
					echo "</pre>";

					echo "<pre>";
					echo 'the_title_attribute(): '.the_title_attribute();
					echo "</pre>";

					echo "<pre>";
					echo 'the_title(): '.the_title();
					echo "</pre>";

					echo "<pre>";
					echo 'the_time("F jS,Y"): '.the_time('F jS,Y')."</pre>";

					echo "<pre>";
					echo 'the_author(): '.the_author();
					echo "</pre>";

					echo "<pre>";
					echo 'the_content(): '.the_content();
					echo "</pre>";

					echo "<pre>";
					echo 'the_tags("Tags: ", ", ", "<br />"): '.the_tags('Tags: ', ', ', '<br />');
					echo "</pre>";

					echo "<pre>";
					echo 'the_category(", "): '.the_category();
					echo "</pre>";
				}

			?>
			</li>

		<?php endwhile; ?>

		</ol>

	<?php else : ?>
		<p class="search-result">NO RESULT(S)</p>
		<h1 style="font-weight:normal;">Sorry we couldn't return any results for <span class="orange-text">"<?php the_search_query() ?>"</span>. Please try another search term and/or check your spelling.</h1>
		<br>
		<?php locate_template("searchform-404.php", true, true); ?>

	<?php endif; ?>

	</div><!-- #content -->
</div><!-- #primary -->

<?php get_footer(); ?>