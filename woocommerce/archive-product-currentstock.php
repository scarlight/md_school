<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header("product-breadcrumb"); ?>

	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action('woocommerce_before_main_content');
	?>

	<?php /************************************ Massdata Theme Start ***************************/ ?>
	
	<?php do_action( 'woocommerce_archive_description' ); ?>

	<?php

	$menu_to_show = array("current-stock" => 0);

	if(is_shop())
	{
		// INFO: get_terms - Retrieve the terms in a taxonomy or list of taxonomies.
		// $all_product_terms = get_terms('product_cat', array('orderby' => 'name', 'orderby' => 'ASC', 'hide_empty' => false));
		
		$args = array(
			'type'                     => 'product',
			'child_of'                 => 0,
			'parent'                   => '',
			'orderby'                  => 'name',
			'order'                    => 'ASC',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'exclude'                  => 'current-stock',
			'include'                  => '',
			'number'                   => '',
			'taxonomy'                 => 'product_cat',
			'pad_counts'               => false 

		); 

		// INFO : get_categories - Returns an array of category objects matching the query parameters.
		// Why use this? Answer: I can use the ordering from Product Categories page for custom sort order
		$all_product_terms = get_categories( $args );

		//menu css are from web inspector from product category page
		echo '<ul id="item-categories-menu">';
		foreach ($all_product_terms as $key => $value) {
			if($value->category_parent != 0)
			{
				echo '<li id="menu-item-'.$value->cat_ID.'" class="menu-item menu-item-type-taxonomy menu-item-object-product_cat menu-item-'.$value->cat_ID.'"><a href="'.get_term_link( $value->slug, $value->taxonomy ).'">'.$value->cat_name.'</a></li>';
			}
		}
		echo '</ul>';
	}

	if(is_tax( 'product_cat' ) && is_archive())
	{
		global $wp_query;

		$category_obj = $wp_query->get_queried_object();

		if($category_obj){
		    $category_name = $category_obj->slug;
		    $category_child_parent_id = $category_obj->parent;  
		    
		    //check category_obj is it a child
		    if($category_child_parent_id != 0){

		    	$category_parent_obj = get_term_by('id', $category_child_parent_id, "product_cat");
		    	$category_parent_slug = $category_parent_obj->slug;

		    	foreach ($menu_to_show as $key => $value) {
		    		if($category_parent_slug == $key){
		    			$menu_to_show[$key] = 1;
		    		}
		    		else{
		    			$menu_to_show[$key] = 0;
		    		}
		    	}
		    	
		    }
		    //so category_obj is a parent
		    else{
		    	foreach ($menu_to_show as $key => $value) {
					if($category_name == $key){
						$menu_to_show[$key] = 1;
					}
					else{
						$menu_to_show[$key] = 0;
					}
				}
		    }
		}
	}

	if($menu_to_show["current-stock"]){
		$current_stock_categories_defaults = array(
		    'theme_location'  => 'current-stock-categories-menu',
		    'menu'            => '',
		    'container'       => 'div',
		    'container_class' => '',
		    'container_id'    => 'custom_list_five',
		    'menu_class'      => '',
		    'menu_id'         => 'item-categories-menu',
		    'echo'            => true,
		    'fallback_cb'     => 'wp_page_menu',
		    'before'          => '',
		    'after'           => '',
		    'link_before'     => '',
		    'link_after'      => '',
		    'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		    'depth'           => 0,
		    'walker'          => ''
		);
		wp_nav_menu( $current_stock_categories_defaults );
	}
	
	?>
	    <div class="clear" style="padding-bottom:10px;"></div>
	    </div>
	    <div class="clear"></div>
	</div>


	<div id="powerista-body2-wrp">

	<?php if(!is_shop()) : ?> 
	    <div id="title-bar2-wrp">
	        <div>
	            <h1 class="title-bar">
	            	<?php echo woocommerce_page_title(); ?>
	            </h1>
	        </div>
	    </div>
	<?php endif; ?>

	    <div id="powerista-body2">

	        <?php if ( have_posts() ) : ?>

	        	<?php
	        		/**
	        		 * woocommerce_before_shop_loop hook
	        		 *
	        		 * @hooked woocommerce_result_count - 20
	        		 * @hooked woocommerce_catalog_ordering - 30
	        		 */
	        		do_action( 'woocommerce_before_shop_loop' );
	        	?>

	        	<?php woocommerce_product_loop_start(); ?>

	        		<?php woocommerce_product_subcategories(); ?>

	        		<?php while ( have_posts() ) : the_post(); ?>

	        			<?php woocommerce_get_template_part( 'content', 'product-currentstock' ); ?>

	        		<?php endwhile; // end of the loop. ?>

	        	<?php woocommerce_product_loop_end(); ?>

	        	<br>
	        	<br>

	        	<?php
	        		/**
	        		 * woocommerce_after_shop_loop hook
	        		 *
	        		 * @hooked woocommerce_pagination - 10
	        		 */
	        		do_action( 'woocommerce_after_shop_loop' );
	        	?>

	        <?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

	        	<?php woocommerce_get_template( 'loop/no-products-found.php' ); ?>

	        <?php endif; ?>

	    </div>
	</div>

	<?php /************************************ Massdata Theme End ***************************/ ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action('woocommerce_after_main_content');
	?>

	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		//do_action('woocommerce_sidebar');
	?>

<?php get_footer("product"); ?>