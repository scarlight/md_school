<?php
/**
 * Created by PhpStorm.
 * User: User_Yam
 * Date: 12/19/13
 * Time: 5:32 PM
 */

/////////////THIS PART IS FOR STOCK UPDATE FROM FORM//////////////////////////////////
function get_massdata_stock($product_variant_id){
    $stock = get_post_meta($product_variant_id, '_stock', true);
    if(!isset($stock)){
        $stock = ' - ';
    }
    return $stock;
}
function get_variant_price($product_variant_id){
    $get_current_user_role = wp_get_current_user()->roles[0];
    $price = null;
    if ($get_current_user_role === 'agent') {
        $price = get_post_meta($product_variant_id, '_massdata_agent_price', true);
    } else if ($get_current_user_role == 'corporate') {
        $price = get_post_meta($product_variant_id, '_massdata_corporate_price', true);
    }
    if(!empty($price)){
        if(!is_float($price)){
            $price = $price.'.00';
        }
    }else{
        $price = ' - ';
    }
    return $price;
}
function get_variant_quantity($product_variant_id){
    $quantity = get_post_meta($product_variant_id, '_massdata_quantity', true);
    if(!isset($quantity)){
        $quantity = ' - ';
    }
    return $quantity;
}
function get_variant_color_name($product_variant_id){
    $color = get_post_meta($product_variant_id, 'attribute_pa_color', true); // get color meta
    $color_temp = explode('-', $color); // rename to a proper readable capital character for words
    if (isset($color_temp)) {
        foreach ($color_temp as $i => $word) {
            $color_temp[$i] = ucfirst($color_temp[$i]);
        }
        $color = implode(' ', $color_temp);
    } else {
        $color = ucfirst($color);
    }
    return $color;
}
function get_variant_attribute_name($product_variant_id){
    $color = get_post_meta($product_variant_id, 'attribute_pa_color', true);
    return $color;
}
function get_post_massdata_reserve($product_id, $user_id){

    $query = new WP_Query(
        array(
            'post_type' => 'massdata_reserve',
            'post_author' => $user_id,
            'post_status' => 'private',
            's' => $product_id
        )
    );
    if(!empty($query->posts)){
        return $query->posts[0];
    }
    return null;
}
function has_reserved($product_id, $user_id){

    $queried = new WP_Query(
        array(
            'post_type' => 'massdata_reserve',
            'post_author' => $user_id,
            'post_content' => $product_id,
            'fields' => 'ids',
            'posts_per_page' => -1
        )
    );
    $queried = $queried->posts;
    return $queried;
}
function get_stock_available($product_post_id){

    $query = new WP_Query(
        array(
            'post_type' => 'massdata_reserve',
            'post_content' => $product_post_id,
            'posts_per_page' => -1,
            'fields' => 'ids'
        )
    );

    $bigarr = array();
    foreach($query->posts as $index => $ids){

        //denormlaize first
        $temp = array();
        $a = get_post_meta($query->posts[$index]);
        unset($a['_edit_lock']);

        foreach($a as $ids => $stock_count){
            $temp[$ids] = $stock_count[0];
        }
        $bigarr[] = $temp;
        //denormalized array passed into $bigarr
    }

    $reserved_stock_counts = array();
    foreach($bigarr as $index => $array){
        foreach($array as $id => $reserve_count){
            if(!isset($reserved_stock_counts[$id])){
                $reserved_stock_counts[$id] = 0;
                $reserved_stock_counts[$id] += $reserve_count;
            }else{
                $reserved_stock_counts[$id] += $reserve_count;
            }
        }
    }

    return $reserved_stock_counts;
}


////////THIS PART IS FOR SHOWING ROW AND DATA ON THE RESERVATION COLUMN//////////////////////
function get_available_stock($stock, $reserve)
{
    if ($reserve) {
        return $stock_available = $stock - $reserve;
    } else {
        return $stock_available = $stock - 0;
    }
}
function get_my_reserve($post_massdata_reserved, $product_variant_id)
{
    if (isset($post_massdata_reserved)) {

        $id = $post_massdata_reserved->ID;
        return $current_user_reserved_stock = get_post_meta($id, $product_variant_id, true);
    } else {
        return $current_user_reserved_stock = 0;
    }
}
function display_my_reserve($attribute_name, $my_reserve, $reserved_id, $reserved, $product_variant_id)
{
    $user = wp_get_current_user();
    if(in_array('administrator', (array)$user->roles)){

    }else if (is_user_logged_in()) { // do this if there are users logged in
        if ($my_reserve != 0) {

            echo "<input type=\"text\" id=\"reserve1\" name=\"reserve_{$attribute_name}:{$product_variant_id}\" value=\"{$my_reserve}\" class=\"form-control\">";
        } else if (!is_null($reserved_id)) { // do this if previous reservation exist

            $my_reserve = get_post_meta($reserved_id, $product_variant_id, true);
            echo "<input type=\"text\" id=\"reserve1\" name=\"reserve_{$attribute_name}:{$product_variant_id}\" value=\"{$my_reserve}\" class=\"form-control\">";
        } else {

            echo "<input type=\"text\" id=\"reserve1\" name=\"reserve_{$attribute_name}:{$product_variant_id}\" value=\"0\" class=\"form-control\">";
        }
    } else {
        if ($reserved != 0) { // user reserved stock
            echo "{$reserved}";
        } else {
            echo "0";
        }
    }
}
function display_global_reserve($reserve)
{
    global $product;
    if (is_user_logged_in()) {

        if ($product->product_type === 'variable')

            echo "<td>{$reserve}</td>";
    }
}
function echo_column_my_reserve()
{
    if (is_user_logged_in()) {
        ?>
        <th rowspan="2">My Reserve</th>
    <?php
    }
}
function echo_column_price_table()
{
    if (is_user_logged_in()) {
        ?>
        <th colspan="2" class="sky-blue">Price Table</th>
    <?php
    }
}
function echo_columns_quantity_and_price()
{
    if (is_user_logged_in()) {
        ?>
        <tr>
            <td class="sky-blue">Quantity</td>
            <td class="sky-blue">Price (RM)</td>
        </tr>
    <?php } else { ?>
        <tr>
        </tr>
    <?php
    }
}
function echo_button_apply_optioncharges()
{
    if (is_user_logged_in()) {
        global $product;
        $user = wp_get_current_user();
        ?>
        <?php if (isset($product->product_type) && $product->product_type === 'variable' && !in_array('administrator', (array)$user->roles)) { ?>
            <input type="submit" name="test" style="margin-right:10px;" class="btn btn-default" value="Apply Now">
        <?php } ?>
        <a href="#" class="btn btn-default view-charges">View Logo option Charges</a>
        <?php

        $custom_query = new WP_Query('page_id=1157');
        while ($custom_query->have_posts()) : $custom_query->the_post();
            the_content();
        endwhile;
        wp_reset_postdata(); // reset the query
        ?>
    <?php
    }
}
function echo_last_row_product_not_variable(){
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
function echo_row_available_stock($total_available_stock)
{
    global $product;
    if ($product->product_type === 'variable') {
        ?>
        <td> <?php echo $total_available_stock; ?> </td>
    <?php } else { ?>
        <td></td>
    <?php
    }
}
function echo_row_all_reserved($total_all_reserved)
{
    global $product;
    if ($product->product_type === 'variable') {
        ?>
        <td><?php echo $total_all_reserved; ?></td>
    <?php } else { ?>
        <td></td>
    <?php
    }
}

function echo_row_user_logged_in()
{
    if (is_user_logged_in()) {
        ?>
        <td></td>
    <?php
    }
}
function echo_row_total_variant_stock($total_variant_stock)
{
    global $product;
    if ($product->product_type === 'variable') {
        ?>
        <td><?php echo $total_variant_stock; ?></td>
    <?php } else { ?>
        <td></td>
    <?php
    }
}
function echo_row_double_user_logged_in()
{
    if (is_user_logged_in()) {
        ?>
        <td></td>
        <td></td>
    <?php
    }

}
function display_total_stock($stock)
{
    if ($stock != 0) {
        echo "<td>{$stock}</td>";
    } else {
        echo "<td>0</td>";
    }
}
function echo_row_total_my_reserve($my_reserve)
{
    $user = wp_get_current_user();
    if(in_array('administrator', (array)$user->roles)){
        echo "<td></td>";
    }else if(is_user_logged_in()){
        echo "<td>{$my_reserve}</td>";
    }
}
function display_row_quantity_price_table($product_variant_id, $quantity)
{
    global $product;
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
}