<?php

require_once get_template_directory() . '/md_reservation_functions.php';

global $product;
global $post;
$post_massdata_reserved = get_post_massdata_reserve($post->ID, get_current_user_id()); // checks whether this product the user has reserved, id yes return the post data, else null.
$post_massdata_all_reserved = get_post_massdata_all_reserve($post->ID);
$message = null;
$reserved_id = null;

if (isset($_POST['test']) && $_POST['test'] == 'Apply Now') {

    $user = wp_get_current_user();
    if (in_array('administrator', (array)$user->roles)) {
        $message = 'Administrator can not reserve stocks. Stock reservation is for Agent and Corporate users';
    } else {

        unset($_POST['test']); // i just need the ids..
        $reservation_arr = array();
        $count = 0;

        if (count($product->children) == count($_POST)) { // count of product children is same as the number of product variant send from form
            foreach ($_POST as $variable_name => $reserve_stock) {

                if (isset($reserve_stock)) { // is reservation count set?

                    $reserve_stock = (int)$reserve_stock;
                    if ($reserve_stock >= 0 && (strlen($reserve_stock) > 0 && strlen($reserve_stock) < 10) && array_sum($_POST) != 0) { // if reservation count is valid

                        if (isset($stock_available_arr[$product->children[$count]])) { // if there are existing reservations.

                            if ($reserve_stock > $stock_available_arr[$product->children[$count]]) { // if reservation is more that stock available

                                $message = 'Product stock reservation cannot exceed the available stock';
                                break;
                            } else { // if reservation is less than stock available

                                $reservation_arr[$product->children[$count]] = $reserve_stock;
                            }
                        } else { // if there is no existing reservations, get stock count of product

                            $product_stock_quantity = get_post_meta($product->children[$count], '_stock', true);
                            if (isset($product_stock_quantity)) {

                                if ($reserve_stock > $product_stock_quantity) { // if reservation is more than stock quantity

                                    $message = 'Product stock reservation cannot exceed the stock quantity';
                                    break;
                                } else { // add reservation count

                                    $reservation_arr[$product->children[$count]] = $reserve_stock;
                                }

                            } else {
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

                // putting data in
                // get reserved stock of all reservation for variable of this product, and update _reserve_stock quantity of product_variant
                $args = array(
                    'post_type' => 'massdata_reserve',
                    'post_status' => 'private',
                    'fields' => 'ids',
                    'posts_per_page' => -1,
                    's' => $product->id
                );
                $query = new WP_Query($args);

                $reserved_all_count = array();
                foreach ($reservation_arr as $reserved_variable_id => $reserved_stock) { //get the ids

                    $reserved_all_count[$reserved_variable_id] = 0;
                    foreach ($query->posts as $j => $post_id) {
                        // go through the reservation stock row by row.
                        $a = get_post_meta($post_id, $reserved_variable_id, true);
                        $reserved_all_count[$reserved_variable_id] = $reserved_all_count[$reserved_variable_id] + $a;
                    }
                    update_post_meta($reserved_variable_id, '_reserve_stock', $reserved_all_count[$reserved_variable_id]);
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

                // add/update reserved stock
                foreach ($reservation_arr as $id => $reserved_stock) {
                    update_post_meta($reserve_post_id, $id, $reserved_stock);
                }

                // putting data in
                // get reserved stock of all reservation for variable of this product, and update _reserve_stock quantity of product_variant
                $args = array(
                    'post_type' => 'massdata_reserve',
                    'post_status' => 'private',
                    'fields' => 'ids',
                    'posts_per_page' => -1,
                    's' => $product->id
                );
                $query = new WP_Query($args);

                $reserved_all_count = array();
                foreach ($reservation_arr as $reserved_variable_id => $reserved_stock) { //get the ids

                    $reserved_all_count[$reserved_variable_id] = 0;
                    foreach ($query->posts as $j => $post_id) {
                        // go through the reservation stock row by row.
                        $a = get_post_meta($post_id, $reserved_variable_id, true);
                        $reserved_all_count[$reserved_variable_id] = $reserved_all_count[$reserved_variable_id] + $a;
                    }
                    update_post_meta($reserved_variable_id, '_reserve_stock', $reserved_all_count[$reserved_variable_id]);
                }

                $message = "Product reservation successfully applied";
                $reserved_id = $reserve_post_id;
            }
        }
    }
}


if (!is_null($message)) {
    echo "<p><strong>{$message}</strong></p>";
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
                <?php if (is_user_logged_in()) { ?>
                    <th rowspan="2">My Reserve</th>
                <?php } ?>
                <th rowspan="2">Reserved</th>
                <th rowspan="2">Total Stock<br><span class="stock-remark">(Stock Available + Reserved)</span></th>
                <?php if (is_user_logged_in()) { ?>
                    <th colspan="2" class="sky-blue">Price Table</th>
                <?php } ?>
            </tr>
            <?php if (is_user_logged_in()) { ?>
                <tr>
                    <td class="sky-blue">Quantity</td>
                    <td class="sky-blue">Price (RM)</td>
                </tr>
            <?php } else { ?>
                <tr>
                </tr>
            <?php } ?>

            <?php

            // $stock_available_arr = get_stock_available($post->ID);
            if ($product->product_type === 'variable') {
                $total_variant_stock = 0; // works
                $current_user_reserved_stock = 0; // works
                $total_current_user_reserved_stock = 0; //
                $total_available_stock = 0;
                $total_all_reserved = 0;

                foreach ($product->children as $index => $product_variant_id) {

                    $stock = get_variant_stock($product_variant_id);
                    $reserve = get_post_meta($product_variant_id, '_reserve_stock', true);
                    if ($reserve) {
                        $stock_available = $stock - $reserve;
                    } else {
                        $stock_available = $stock - 0;
                    }
                    $quantity = get_variant_quantity($product_variant_id);
                    $color = get_variant_color_name($product_variant_id);
                    $variable_name = get_variant_variable_name($product_variant_id);

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
                    if (is_user_logged_in()) {
                        if ($current_user_reserved_stock != 0) { // user reserved stock

                            echo "<input type=\"text\" id=\"reserve1\" name=\"reserve_{$variable_name}\" value=\"{$current_user_reserved_stock}\" class=\"form-control\">";
                        } else if (!is_null($reserved_id)) {

                            $current_user_reserved_stock = get_post_meta($reserved_id, $product_variant_id, true);
                            echo "<input type=\"text\" id=\"reserve1\" name=\"reserve_{$variable_name}\" value=\"{$current_user_reserved_stock}\" class=\"form-control\">";
                        } else {

                            echo "<input type=\"text\" id=\"reserve1\" name=\"reserve_{$variable_name}\" value=\"0\" class=\"form-control\">";
                        }
                    } else {
                        if ($reserve != 0) { // user reserved stock

                            echo "{$reserve}";
                        } else {

                            echo "0";
                        }
                    }
                    echo "</td>";

                    if (is_user_logged_in()) {

                        if ($product->product_type === 'variable') {
                            echo "<td>{$reserve}</td>";
                        } else {
                            echo "<td>0</td>";
                        }
                    }

                    if ($stock != 0) {
                        echo "<td>{$stock}</td>";
                    } else {
                        echo "<td>0</td>";
                    }
                    if (is_user_logged_in()) {
                        if ($product->product_type === "variable") {
                            $price = get_variant_price($product_variant_id);
                            echo "<td>{$quantity}</td>"; // product quantity for price
                            echo "<td>{$price}</td>"; // product price per quantity
                        } else {
                            echo "<td></td>";
                            echo "<td></td>";
                        }
                    }
                    echo '</tr>';

                    if ($product->product_type === "variable") {
                        $total_variant_stock = $total_variant_stock + $stock;
                        $total_current_user_reserved_stock = $total_current_user_reserved_stock + $current_user_reserved_stock;
                        $total_available_stock = $total_available_stock + $stock_available;
                        $total_all_reserved = $total_all_reserved + $reserve;
                    }
                }
            } else {

                if (is_user_logged_in()) {

                    echo "<tr>";
                    echo "<td>0</td>"; //product color
                    echo "<td>0</td>"; //product stock
                    echo "<td>0</td>";
                    echo "<td>0</td>";
                    echo "<td>0</td>";
                    echo "<td>0</td>";
                    echo "<td>0</td>";
                    echo "</tr>";
                } else {

                    echo "<tr>";
                    echo "<td>0</td>"; //product color
                    echo "<td>0</td>"; //product stock
                    echo "<td>0</td>";
                    echo "<td>0</td>";
                    echo "</tr>";
                }
            }

            ?>

            <tr class="total-stock-count">
                <td>Total</td>

                <?php if ($product->product_type === 'variable') { ?>
                    <td> <?php echo $total_available_stock; ?> </td>
                    <td><?php echo $total_all_reserved; ?></td>
                <?php } else { ?>
                    <td></td>
                    <td></td>
                <?php } ?>

                <?php if (is_user_logged_in()) { ?>
                    <td></td>
                <?php } ?>

                <?php if ($product->product_type === 'variable') { ?>
                    <td><?php echo $total_variant_stock; ?></td>
                <?php } else { ?>
                    <td></td>
                <?php } ?>

                <?php if (is_user_logged_in()) { ?>
                    <td></td>
                    <td></td>
                <?php } ?>

            </tr>
            </tbody>
        </table>
        <br>
        <div class="floatl">
            <div class="red-remark">*Stock level as at 4pm yesterday</div>
            <div class="red-remark">*Reserved only valid for 3 days</div>
        </div>
        <?php if (is_user_logged_in()) { ?>
            <div class="floatr">
                <?php if(isset($product->product_type) && $product->product_type === 'variable'){ ?>
                <input type="submit" name="test" style="margin-right:10px;" class="btn btn-default" value="Apply Now">
                <? } ?>
                <a href="#" class="btn btn-default">View Logo option Charges</a>
            </div>
        <?php } ?>
        <div class="clear"></div>
    </form>