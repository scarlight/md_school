<?php
/**
 * Single Product title
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;

$the_product_custom_fields = array(
      'massdata_product_type' => "",
      'massdata_product_model' => "",
      'massdata_product_dimension_length_x_width_x_height' => "",
      'massdata_product_logo_options' => "",
      'massdata_product_print_area' => "",
      'massdata_product_packaging_details' => "",
      'massdata_product_units_per_carton' => "",
      'massdata_product_units_per_inner_box' => "",
      'massdata_product_weight_per_carton_kgs' => "",
      'massdata_product_carton_size_mm' => "",
      'massdata_product_est_date_of_stock_arrival' => "",
      'massdata_product_remarks' => ""
  );

foreach ( $the_product_custom_fields as $key => $value ) {
    $the_product_custom_fields[$key] = get_post_meta($post->ID, $key, true);
}

extract($the_product_custom_fields);

?>

<h1 class="item-code"><?php echo $post->post_title; ?></h1>
<h2><span class="pencil-icon"></span> Product Specification</h2>
<table>
    <tr>
        <td class="desc">Type</td>
        <td>:</td>
        <td class="detail"><?php echo !empty($massdata_product_type) ? $massdata_product_type : "-"; ?></td>
    </tr>
    <tr>
        <td class="desc">Model</td>
        <td>:</td>
        <td class="detail"><?php echo !empty($massdata_product_model) ? $massdata_product_model : "-"; ?></td>
    </tr>
    <tr>
        <td class="desc">Dimension (L x W x H)</td>
        <td>:</td>
        <td class="detail"><?php echo !empty($massdata_product_dimension_length_x_width_x_height) ? $massdata_product_dimension_length_x_width_x_height : "-"; ?></td>
    </tr>
    <tr>
        <td class="desc">Logo Options</td>
        <td>:</td>
        <td class="detail"><?php echo !empty($massdata_product_logo_options) ? $massdata_product_logo_options : "-"; ?></td>
    </tr>
    <tr>
        <td class="desc">Print Area</td>
        <td>:</td>
        <td class="detail"><?php echo !empty($massdata_product_print_area) ? $massdata_product_print_area : "-"; ?></td>
    </tr>
    <tr>
        <td class="desc">Packaging Details</td>
        <td>:</td>
        <td class="detail"><?php echo !empty($massdata_product_packaging_details) ? $massdata_product_packaging_details : "-"; ?></td>
    </tr>
    <tr>
        <td class="desc">Units per Carton</td>
        <td>:</td>
        <td class="detail"><?php echo !empty($massdata_product_units_per_carton) ? $massdata_product_units_per_carton : "-"; ?></td>
    </tr>
    <tr>
        <td class="desc">Units per Inner Box</td>
        <td>:</td>
        <td class="detail"><?php echo !empty($massdata_product_units_per_inner_box) ? $massdata_product_units_per_inner_box : "-"; ?></td>
    </tr>
    <tr>
        <td class="desc">Weight per Carton (kgs)</td>
        <td>:</td>
        <td class="detail"><?php echo !empty($massdata_product_weight_per_carton_kgs) ? $massdata_product_weight_per_carton_kgs : "-"; ?></td>
    </tr>
    <tr>
        <td class="desc">Carton Size (mm)</td>
        <td>:</td>
        <td class="detail"><?php echo !empty($massdata_product_carton_size_mm) ? $massdata_product_carton_size_mm : "-"; ?></td>
    </tr>
    <tr>
        <td class="desc">Est. Date of Stock Arrival <br> <span class="red-remark">(Subject to change)</span></td>
        <td>:</td>
        <td class="detail"><?php echo !empty($massdata_product_est_date_of_stock_arrival) ? $massdata_product_est_date_of_stock_arrival : "-"; ?></td>
    </tr>
    <tr>
        <td class="desc">Remarks</td>
        <td>:</td>
        <td class="detail"><?php echo !empty($massdata_product_remarks) ? $massdata_product_remarks : "-"; ?></td>
    </tr>
</table>