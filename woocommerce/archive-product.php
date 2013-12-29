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

	// echo "<pre>";
	//     $args = array(
	//         'orderby'       => 'name',
	//         'order'         => 'ASC',
	//         'hide_empty'    => true,
	//         'exclude'       => array(),
	//         'exclude_tree'  => array(),
	//         'include'       => array(),
	//         'number'        => '',
	//         'fields'        => 'all',
	//         'slug'          => '',
	//         'parent'         => '',
	//         'hierarchical'  => true,
	//         'child_of'      => 0,
	//         'get'           => '',
	//         'name__like'    => '',
	//         'pad_counts'    => false,
	//         'offset'        => '',
	//         'search'        => '',
	//         'cache_domain'  => 'core'
	//     );
	//     $all_product_categories = get_terms( 'product_cat', $args ); //get all the fields for each products category

	//     print_r($all_product_categories);
 	//     $prod_categories = array(); //put value into an array for all the parent field 
 	//	   foreach ($all_product_categories as $values) {
 	//			$cat_name = $values->name;
 	//    		$prod_categories[$cat_name] = $values->parent;
	// 	   }

	// 	$cat_repeat = array_count_values($prod_categories); 
	// 	$cat_highest_repeat = array_search(max($cat_repeat), $cat_repeat); //get the highest repeating parent field value
	// 	echo "$cat_highest_repeat > ".$cat_highest_repeat;
	// 	$possible_category_term = get_term( $cat_highest_repeat, 'product_cat');

	// 	print_r($possible_category_term->name);
		
	// echo "</pre>";

	$menu_to_show = array(
		"popular-pen-drive" => 0,
		"designer-pen-drive" => 0,
		"usb-gadget" => 0,
		"electronic-gadget" => 0
		);

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
			'exclude'                  => '16,39,40,41,42',
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

	if($menu_to_show["popular-pen-drive"]){
		$popular_pendrive_categories_defaults = array(
		    'theme_location'  => 'popular-pendrive-categories-menu',
		    'menu'            => '',
		    'container'       => '',
		    'container_class' => '',
		    'container_id'    => '',
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
		wp_nav_menu( $popular_pendrive_categories_defaults );
	}
	elseif($menu_to_show["designer-pen-drive"]){
		$designer_pendrive_categories_defaults = array(
		    'theme_location'  => 'designer-pendrive-categories-menu',
		    'menu'            => '',
		    'container'       => '',
		    'container_class' => '',
		    'container_id'    => '',
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
		wp_nav_menu( $designer_pendrive_categories_defaults );
	}
	elseif($menu_to_show["usb-gadget"]){
		$usb_gadget_categories_defaults = array(
		    'theme_location'  => 'usb-gadget-categories-menu',
		    'menu'            => '',
		    'container'       => '',
		    'container_class' => '',
		    'container_id'    => '',
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
		wp_nav_menu( $usb_gadget_categories_defaults );
	}
	elseif($menu_to_show["electronic-gadget"]){
		$electronic_gadget_categories_defaults = array(
		    'theme_location'  => 'electronic-gadget-categories-menu',
		    'menu'            => '',
		    'container'       => '',
		    'container_class' => '',
		    'container_id'    => '',
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
		wp_nav_menu( $electronic_gadget_categories_defaults );
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
	            	<?php 
	            		if ( apply_filters( 'woocommerce_show_page_title', true ) && is_tax( 'product_cat' ) && is_archive()){
	            			ob_start();
	            			woocommerce_page_title();
	            			$massdata_tax_category_title = ob_get_contents();
	            			ob_end_clean();
	            			
	            			if(("Popular Pen Drive" == $massdata_tax_category_title) || ("Designer Pen Drive" == $massdata_tax_category_title)){
	            				echo 'All Pen Drives';
	            			}
	            			else{
	            				echo $massdata_tax_category_title;
	            			}
	            		}
	            	?>
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

	        		<?php woocommerce_product_subcategories(); //shown when woocommerce > settings > catalog > Default Category Display > is subcategories or both ?>

		        		<?php while ( have_posts() ) : the_post(); ?>

		        			<?php woocommerce_get_template_part( 'content', 'product' ); ?>

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