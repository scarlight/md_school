<?php

require_once get_template_directory() . '/md_reservation_functions.php';
//_massdata_quantity
//_massdata_agent_price
//_massdata_corporate_price
//attribute_pa_color
//_stock
global $product;
global $post;

$message = null;
if (isset($_POST['test']) && $_POST['test'] == 'Apply Now') {

    unset($_POST['test']);

    $reservation_arr = array();
    $count = 0;

    if(count($product->children) == count($_POST)){
        foreach($_POST as $variable_name => $reserve_stock){

            if(isset($reserve_stock)){
                if(is_numeric($reserve_stock) && strlen($reserve_stock) >= 0 && strlen($reserve_stock) < 10){

                    $reservation_arr[$product->children[$count]] = $reserve_stock;

                }else{
                    $message = 'Please enter a valid number for product stock reservation';
                    break;
                }
            }
        $count++;
        }
    }

    if(is_null($message)){

        $reserve_post_args = array(
            'post_author' => get_current_user_id(),
            'post_content' => $product->id,
            'post_status' => 'private',
            'post_type' => 'massdata_reserve'
        );
        $reserve_post_id = wp_insert_post($reserve_post_args);

        $update_reserve_post_args = array(
            'ID' => $reserve_post_id,
            'post_title' => 'Reservation #'.$reserve_post_id
        );
        wp_update_post($update_reserve_post_args);

        foreach($reservation_arr as $id => $reserved_stock){
            update_post_meta($reserve_post_id, $id, $reserved_stock);
        }
        $message = "Quotation successfully sent";
    }
}



if(!is_null($message)){
    echo "<p>{$message}</p>";
}
?>

<form action="" method="post">
    <table class="product-stock">
        <thead>
        </thead>
        <tbody>
        <tr>
            <th rowspan="2">Product Color</th>
            <th rowspan="2">Stock Available</th>
            <th rowspan="2">Reserved</th>
            <th rowspan="2">Total Stock<br><span class="stock-remark">(Stock Available + Reserved)</span></th>
            <th colspan="2" class="sky-blue">Price Table</th>
        </tr>
        <tr>
            <td class="sky-blue">Quantity</td>
            <td class="sky-blue">Price (RM)</td>
        </tr>

        <?php
        foreach ($product->children as $index => $product_variant_id) {

            $stock = get_variant_stock($product_variant_id);
            $quantity = get_variant_quantity($product_variant_id);
            $color = get_variant_color_name($product_variant_id);
            $variable_name = get_variant_variable_name($product_variant_id);
            $price = get_variant_price($product_variant_id);

            echo "<tr>";
            echo "<td>{$color}</td>"; //product color
            echo "<td>{$stock}</td>";
            echo "<td>";
            echo "<input type=\"text\" id=\"reserve1\" name=\"reserve_{$variable_name}\" value=\"0\" class=\"form-control\">";
            echo "</td>";
            echo "<td> </td>";
            echo "<td>{$quantity}</td>";
            echo "<td>{$price}</td>";
            echo '</tr>';
        }
        ?>
        <tr class="total-stock-count">;
            <td>Total</td>;
            <td> <?php echo get_total_stock($post->ID); ?> </td>
            <td>0</td>
            <td>80</td>
            <td></td>
            <td></td>
            </tr>
        </tbody>
    </table
    <div class="floatl">
        <div class="red-remark">*Stock level as at 4pm yesterday</div>
        <div class="red-remark">*Reserved only valid for 3 days</div>
    </div>
    <div class="floatr">
        <input type="submit" name="test" style="margin-right:10px;" class="btn btn-default" value="Apply Now">
        <a href="#" class="btn btn-default">View Logo option Charges</a>
    </div>
    <div class="clear"></div>
</form>
