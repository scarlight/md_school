<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked woocommerce_show_messages - 10
	 */
	 do_action( 'woocommerce_before_single_product' );
?>

<div id="product-spec">
    <div itemscope itemtype="http://schema.org/Product" id="product-<?php the_ID(); ?>" <?php post_class(); echo "id=product-spec"; ?>>

    	<?php
    		/**
    		 * woocommerce_show_product_images hook
    		 *
    		 * @hooked woocommerce_show_product_sale_flash - 10
    		 * @hooked woocommerce_show_product_images - 20
    		 */
    		do_action( 'woocommerce_before_single_product_summary' );
    	?>

    	<div class="summary entry-summary item-spec">

    		<?php
    			/**
    			 * woocommerce_single_product_summary hook
    			 *
    			 * @hooked woocommerce_template_single_title - 5
    			 * @hooked woocommerce_template_single_price - 10
    			 * @hooked woocommerce_template_single_excerpt - 20
    			 * @hooked woocommerce_template_single_add_to_cart - 30
    			 * @hooked woocommerce_template_single_meta - 40
    			 * @hooked woocommerce_template_single_sharing - 50
    			 */
    			do_action( 'woocommerce_single_product_summary' );
    		?>

    	</div><!-- .summary -->

    </div><!-- #product-<?php the_ID(); ?> -->

    </div>
    <div class="clear"></div>
</div>

    

    <?php
        /**
         * woocommerce_after_single_product_summary hook
         *
         * @hooked woocommerce_output_product_data_tabs - 10  <----------------< I HAVE REMOVED THE CONTENT IN TABS.PHP FOR DATA TAB AND PLACED IT AT THE BOTTOM INSTEAD FOR LAYOUT CORRECTION
         * @hooked woocommerce_output_related_products - 20
         */
        do_action( 'woocommerce_after_single_product_summary' );
    ?>



</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>