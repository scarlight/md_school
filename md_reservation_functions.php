<?php
/**
 * Created by PhpStorm.
 * User: User_Yam
 * Date: 12/19/13
 * Time: 5:32 PM
 */

function get_variant_stock($product_variant_id){
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
function get_variant_variable_name($product_variant_id){
    $color = get_post_meta($product_variant_id, 'attribute_pa_color', true);
    return $color;
}
function get_post_massdata_reserve($post_id, $user_id){
    $query = new WP_Query(
        array(
            'post_type' => 'massdata_reserve',
            'post_content' => $post_id,
            'post_author' => $user_id
        )
    );
    if(!empty($query->posts)){
        return $query->posts[0];
    }
    return null;
}
function get_stock_available($product_post_id){

    $query = new WP_Query(
        array(
            'post_type' => 'massdata_reserve',
            'post_content' => $product_post_id,
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
function get_reserved_stock($post_id){

    $post_meta = get_post_meta($post_id);

}