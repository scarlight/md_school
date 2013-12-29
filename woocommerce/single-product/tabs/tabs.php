<?php
/**
 * Single Product tabs
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $tabs ) ) : ?>

	<div id="powerista-body2-wrp">
        <div class="bgcolor-gray">
            <div id="powerista-body2">
                <ul id="single-product-tabs" class="nav nav-tabs tab-title">
                    <!-- <li><a href="#tab1">REQUEST QUOTE</a></li> -->
                    <?php foreach ( $tabs as $key => $tab ) : ?>
                        <li class="<?php echo $key ?>_tab">
                            <a href="#tab-<?php echo $key ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <br>
                <div class="tab-content">
                    <?php foreach ( $tabs as $key => $tab ) : ?>
                        <div class="panel entry-content tab-pane fade" id="tab-<?php echo $key ?>">
                            <?php call_user_func( $tab['callback'], $key, $tab ) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>