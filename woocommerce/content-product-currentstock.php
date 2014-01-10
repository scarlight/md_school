<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
	$classes[] = 'first';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
	$classes[] = 'last';
?>

<?php
	//remove the "product" in li class
	ob_start();
	post_class( $classes );
	$li_class = ob_get_contents();
	ob_end_clean();

	$li_class = str_replace(" product ", " ", $li_class);
?>

<li <?php echo $li_class; ?>>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<a href="<?php the_permalink(); ?>">

		<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );
		?>
		
		<?php
			$the_product_custom_fields_model = get_post_meta($post->ID, 'massdata_product_model', true);
			$the_product_custom_fields_type = get_post_meta($post->ID, 'massdata_product_type', true);
		?>
		<span class="stock-sku"><?php echo $the_product_custom_fields_model; ?></span>
		<span class="stock-item-name"><?php echo $the_product_custom_fields_type; ?></span>
        <?php if(isset($product->children) && $product->product_type == 'variable'){
            $get_stock_quantity = 0;
            foreach($product->children as $variable_index => $variable_id){
                $a = get_post_meta($variable_id, '_stock', true);
                $get_stock_quantity = $get_stock_quantity + $a;
            }
        ?>
            <span class="stock-quantity">Quantity : <?php echo $get_stock_quantity; ?></span>
        <?php }else if(is_null($product->children) && $product->product_type == 'variable'){

            $queried = new WP_Query(array(
                'post_parent' => $product->id,
                'posts_per_page' => -1,
                'fields' => 'ids'
            ));
            $get_product_quantity = 0;
            foreach($queried->posts as $index_product => $variable_id){
                $a = get_post_meta($variable_id, '_stock', true);
                $get_product_quantity = $get_product_quantity + $a;
            }
        ?>
            <span class="stock-quantity">Quantity : <?php echo $get_product_quantity; ?></span>
        <?php }else{ ?>
            <span class="stock-quantity">Quantity : <?php echo $product->get_stock_quantity(); ?></span>
        <?php } ?>
        <?php
			/**
			 * woocommerce_after_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
		?>

	</a>

	<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>

</li>