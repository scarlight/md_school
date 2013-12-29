<?php
/**
 * Description tab
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $post;

$heading = esc_html( apply_filters('woocommerce_product_description_heading', __( 'Additional Information', 'woocommerce' ) ) );
?>

<?php if(false){ ?>
<h6><?php echo $heading; ?></h6>
<?php } ?>

<?php the_content(); ?>
