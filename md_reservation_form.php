<?php
function echo_message($message){
    if (!is_null($message)) {
        echo "<p><strong>{$message}</strong></p>";
    }
}
require_once get_template_directory() . '/md_reservation_functions.php';

global $product;
$reservation = get_post_massdata_reserve($product->id, get_current_user_id()); // checks whether this product the user has reserved, id yes return the post data, else null.
$message = null;
$global_reserved_id = null;
$available_stock_arr = get_stock_available($product->id);


if (isset($_POST['test']) && $_POST['test'] == 'Apply Now') {

    $user = wp_get_current_user();
    if (in_array('administrator', (array)$user->roles)) {
        $message = 'Administrator can not reserve stocks. Stock reservation is for Agent and Corporate users';
    } else {
        unset($_POST['test']); // i just need the ids..
        $reservation_arr = array(); // this is just a placeholder to put the reservation when validation is ok

        if (count($product->children) == count($_POST)) { // count of product children is same as the number of product variant send from form
            foreach ($_POST as $form_variable_index => $my_reserve) {

                $temp = explode(":", $form_variable_index);
                $product_variant_id = $temp[1];

                $stock = get_massdata_stock($product_variant_id);
                $reserved = get_post_meta($product_variant_id, '_reserve_stock', true);
                if(isset($reserved)){
                    $available_stock = get_available_stock($stock, $reserved);
                }else{
                    $available_stock = get_available_stock($stock, 0);
                }
                $my_reserve = (int)$my_reserve;

                if (isset($my_reserve)) { // is reservation count set?
                    if ($my_reserve >= 0 && (strlen($my_reserve) > 0 && strlen($my_reserve) < 10) && array_sum($_POST) != 0) { // if reservation count is valid

                        if (isset($reservation)) { // if there are existing reservations.

                            if ($my_reserve <= $available_stock) { // if my_reserve is less than or equal as stock available

                                $reservation_arr[$product_variant_id] = $my_reserve;
                            } else { // if reservation is more than stock available

                                $message = 'Product stock reservation cannot exceed the available stock';
                                break;
                            }
                        } else { // if there is no existing reservations, get stock count of product

                                $reservation_arr[$product_variant_id] = $my_reserve;
                        }

                    } else { // if reservation count is not valid

                        $message = 'Please enter a valid number for product stock reservation';
                        break;
                    }
                }
            }
        }

        if (is_null($message)) {

            if (isset($reservation)) {

                var_dump('isset($reservation)');
                var_dump($reservation);
                foreach ($reservation_arr as $id => $global_reserved_stock) {
                    update_post_meta($reservation->ID, $id, $global_reserved_stock);
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

                $global_reserved_all_count = array();
                foreach ($reservation_arr as $global_reserved_variable_id => $global_reserved_stock) { //get the ids

                    $global_reserved_all_count[$global_reserved_variable_id] = 0;
                    foreach ($query->posts as $j => $post_id) {
                        // go through the reservation stock row by row.
                        $a = get_post_meta($post_id, $global_reserved_variable_id, true);
                        $global_reserved_all_count[$global_reserved_variable_id] = $global_reserved_all_count[$global_reserved_variable_id] + $a;
                    }
                    update_post_meta($global_reserved_variable_id, '_reserve_stock', $global_reserved_all_count[$global_reserved_variable_id]);
                }
                $message = "Product reservation successfully updated";


            } else {

                var_dump('!isset($reservation)');
                var_dump($reservation);

                $global_reserve_post_args = array(
                    'post_type' => 'massdata_reserve',
                    'post_author' => get_current_user_id(),
                    'post_content' => $product->id,
                    'post_status' => 'private'
                );
                $global_reserve_post_id = wp_insert_post($global_reserve_post_args);

                $update_reserve_post_args = array(
                    'ID' => $global_reserve_post_id,
                    'post_title' => 'Reservation #' . (int)$global_reserve_post_id
                );
                wp_update_post($update_reserve_post_args);

                // add/update reserved stock
                foreach ($reservation_arr as $id => $global_reserved_stock) {
                    update_post_meta($global_reserve_post_id, $id, $global_reserved_stock);
                }

                var_dump($global_reserve_post_id);
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

                $global_reserved_all_count = array();
                foreach ($reservation_arr as $global_reserved_variable_id => $global_reserved_stock) { //get the ids

                    $global_reserved_all_count[$global_reserved_variable_id] = 0;
                    foreach ($query->posts as $j => $post_id) {
                        // go through the reservation stock row by row.
                        $a = get_post_meta($post_id, $global_reserved_variable_id, true);
                        $global_reserved_all_count[$global_reserved_variable_id] = $global_reserved_all_count[$global_reserved_variable_id] + $a;
                    }
                    update_post_meta($global_reserved_variable_id, '_reserve_stock', $global_reserved_all_count[$global_reserved_variable_id]);
                }

                $message = "Product reservation successfully applied";
                $global_reserved_id = $global_reserve_post_id;

                $email_user_id = get_current_user_id();
                $email_product_model = get_post_meta($product->id, 'massdata_product_model', true);
                $email_message =<<<"QUOTE_EMAIL"
Product Reservation was sent/updated:-

Reservation ID: {$global_reserve_post_id}
User ID: {$email_user_id}
Product ID: {$product->id}
Product Model: {$email_product_model}
QUOTE_EMAIL;
                wp_mail(get_option('admin_email'), 'Masssdata System: Product Reservation', $email_message);
            }

        }


    }
}
?>
    <?php echo_message($message); ?>
    <form action="" method="post">
        <table class="product-stock">
            <thead>
            </thead>
            <tbody>
            <tr>
                <th rowspan="2">Product Color</th>
                <th rowspan="2">Stock Available</th>
                <?php echo_column_my_reserve(); ?>
                <th rowspan="2">Reserved</th>
                <th rowspan="2">Total Stock<br><span class="stock-remark">(Stock Available + Reserved)</span></th>
                <?php echo_column_price_table(); ?>
            </tr>
            <?php echo_columns_quantity_and_price(); ?>

            <?php
            if ($product->product_type === 'variable') {
                $my_reserve = 0; // works
                $total_stock = 0; // works
                $total_my_reserve = 0; //
                $total_available_stock = 0;
                $total_reserved = 0;

                foreach ($product->children as $product_variant_index => $product_variant_id) {

                    $stock = get_massdata_stock($product_variant_id);
                    $reserved = get_post_meta($product_variant_id, '_reserve_stock', true);
                    $available_stock = get_available_stock($stock, $reserved);
                    $my_reserve = get_my_reserve($reservation, $product_variant_id);

                    $color = get_variant_color_name($product_variant_id);
                    $quantity = get_variant_quantity($product_variant_id);
                    $variable_attribute_name = get_variant_attribute_name($product_variant_id);
                    

                    echo "<tr>";
                        echo "<td>{$color}</td>"; //product color
                        echo "<td>{$available_stock}</td>"; //product stock
                        echo "<td>";
                            display_my_reserve($variable_attribute_name, $my_reserve, $global_reserved_id, $reserved, $product_variant_id);
                        echo "</td>";
                        display_global_reserve($reserved);
                        display_total_stock($stock);
                        display_row_quantity_price_table($product_variant_id, $quantity);
                    echo '</tr>';

                    if ($product->product_type === "variable") {
                        $total_stock += $stock;
                        $total_my_reserve += $my_reserve;
                        $total_available_stock += $available_stock;
                        $total_reserved += $reserved;
                    }
                }
            } else {
                echo_last_row_product_not_variable();
            }

            ?>

            <tr class="total-stock-count">
                <td>Total</td>
                <?php echo_row_available_stock($total_available_stock); ?>
                <?php echo_row_total_my_reserve($total_my_reserve) ?>
                <?php echo_row_all_reserved($total_reserved); ?>
                <?php echo_row_total_variant_stock($total_stock); ?>
                <?php echo_row_double_user_logged_in(); ?>
            </tr>
            </tbody>
        </table>
        <br>
        <div class="floatl">
            <div class="red-remark">*Stock level as at 4pm yesterday</div>
            <div class="red-remark">*Reserved only valid for 3 days</div>
        </div>
            <div class="floatr view-charges">
                <?php echo_button_apply_optioncharges(); ?>
            </div>
        <div class="clear"></div>
    </form>


