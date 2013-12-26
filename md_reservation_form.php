<?php

require_once get_template_directory() . '/md_reservation_functions.php';

global $product;
global $post;
$post_massdata_reserved = get_post_massdata_reserve($post->ID, get_current_user_id()); // checks whether this product the user has reserved, id yes return the post data, else null.
$stock_available_arr = get_stock_available($post->ID);
$message = null;
$reserved_id = null;

if (isset($_POST['test']) && $_POST['test'] == 'Apply Now') {

    unset($_POST['test']); // i just need the ids..
    $reservation_arr = array();
    $count = 0;

    if (count($product->children) == count($_POST)) { // count of product children is same as the number of product variant send from form
        foreach ($_POST as $variable_name => $reserve_stock) {

            if (isset($reserve_stock)) { // is reservation count set?

                $reserve_stock= (int)$reserve_stock;
                if ($reserve_stock > 0 && strlen($reserve_stock) >= 0 && strlen($reserve_stock) < 10) { // if reservation count is valid

                    if(isset($stock_available_arr[$product->children[$count]])){ // if there are existing reservations.

                        if($reserve_stock > $stock_available_arr[$product->children[$count]]){ // if reservation is more that stock available

                            $message = 'Product stock reservation cannot exceed the available stock';
                            break;
                        }else{ // if reservation is less than stock available

                            $reservation_arr[$product->children[$count]] = $reserve_stock;
                        }
                    }else{ // if there is no existing reservations, get stock count of product

                        $product_stock_quantity = get_post_meta($product->children[$count], '_stock', true);
                        if(isset($product_stock_quantity) && is_int($product_stock_quantity)){

                            if($reserve_stock > $product_stock_quantity){ // if reservation is more than stock quantity

                                $message = 'Product stock reservation cannot exceed the stock quantity';
                                break;
                            }else{ // add reservation count

                                $reservation_arr[$product->children[$count]] = $reserve_stock;
                            }

                        }else{
                            $message = 'Something when wrong. Please refresh the page. If error persist, please contact the administrator';
                            break;
                        }
                    }
                } else { // if reservation count is not valid

                    $message = 'Please enter a valid number for product stock reservation';
                    break;
                }
            }
            $count++;
        }
    }

    if (is_null($message)) {

        if (isset($post_massdata_reserved)) {

            foreach ($reservation_arr as $id => $reserved_stock) {
                update_post_meta($post_massdata_reserved->ID, $id, $reserved_stock);
            }
            $message = "Product reservation successfully updated";
        } else {

            $reserve_post_args = array(
                'post_author' => get_current_user_id(),
                'post_content' => $product->id,
                'post_status' => 'private',
                'post_type' => 'massdata_reserve'
            );
            $reserve_post_id = wp_insert_post($reserve_post_args);

            $update_reserve_post_args = array(
                'ID' => $reserve_post_id,
                'post_title' => 'Reservation #' . (int)$reserve_post_id
            );
            wp_update_post($update_reserve_post_args);

            foreach ($reservation_arr as $id => $reserved_stock) {
                update_post_meta($reserve_post_id, $id, $reserved_stock);
            }
            $message = "Product reservation successfully applied";
            $reserved_id = $reserve_post_id;
        }
    }
}

if (!is_null($message)) {
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
        $total_variant_stock = 0;                   // works
        $current_user_reserved_stock = 0;           // works
        $total_current_user_reserved_stock = 0;     //
        $total_available_stock = 0;

        foreach ($product->children as $index => $product_variant_id) {

            $stock = get_variant_stock($product_variant_id);
            if(isset($stock_available_arr[$product_variant_id])){
                $stock_available = $stock - $stock_available_arr[$product_variant_id];
            }else{
                $stock_available = $stock - 0;
            }
            $quantity = get_variant_quantity($product_variant_id);
            $color = get_variant_color_name($product_variant_id);
            $variable_name = get_variant_variable_name($product_variant_id);
            $price = get_variant_price($product_variant_id);

            if (isset($post_massdata_reserved)) {

                $id = $post_massdata_reserved->ID;
                $current_user_reserved_stock = get_post_meta($id, $product_variant_id, true);
            } else {
                $current_user_reserved_stock = 0;
            }

            echo "<tr>";
            echo "<td>{$color}</td>"; //product color
            echo "<td>{$stock_available}</td>"; //product stock
            echo "<td>";
            if ($current_user_reserved_stock != 0) { // user reserved stock

                echo "<input type=\"text\" id=\"reserve1\" name=\"reserve_{$variable_name}\" value=\"{$current_user_reserved_stock}\" class=\"form-control\">";
            } else if (!is_null($reserved_id)) {

                $current_user_reserved_stock = get_post_meta($reserved_id, $product_variant_id, true);
                echo "<input type=\"text\" id=\"reserve1\" name=\"reserve_{$variable_name}\" value=\"{$current_user_reserved_stock}\" class=\"form-control\">";
            } else {

                echo "<input type=\"text\" id=\"reserve1\" name=\"reserve_{$variable_name}\" value=\"0\" class=\"form-control\">";
            }
            echo "</td>";
            echo "<td>{$stock}</td>";
            echo "<td>{$quantity}</td>"; // product quantity for price
            echo "<td>{$price}</td>";    // product price per quantity
            echo '</tr>';


            $total_variant_stock = $total_variant_stock + $stock;
            $total_current_user_reserved_stock = $total_current_user_reserved_stock + $current_user_reserved_stock;
            $total_available_stock = $total_available_stock + $stock_available;
        }
        ?>

        <tr class="total-stock-count">
            <td>Total</td>
            <td> <?php echo $total_available_stock; ?> </td>
            <td><?php echo $total_current_user_reserved_stock; ?></td>
            <td><?php echo $total_variant_stock; ?></td>
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
