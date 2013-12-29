<?php
/**
 * Created by PhpStorm.
 * User: User_Yam
 * Date: 12/19/13
 * Time: 5:32 PM
 */

function get_product_type($product_id){

    $product_post = get_product($product_id);
    return $product_post->product_type; // i hope it returns variable
}
function get_product_colors($product_id){
    //$product_id = index of product
    $product_post = get_post($product_id);
    $colors = array();
    if($product_post->children == null or $product_post->children == 0){
        return array();
    }else{
        foreach($product_post->children as $index => $id){
            $colors = get_post_meta($id, 'attribute_pa_color', true);
        }
    }
    return $colors;
}
function get_product_children($product_id){
    $product_post = get_product($product_id);
    return $product_post;
}
function get_available_stock($product_id){

    $args = array(
        'post_type' => 'massdata_reserve',
        'post_content' => $product_id
    );


}


function get_total_stock($product_id){
    $total_stock =  get_post_meta($product_id, '_variant_total_stock', true);
    return $total_stock;
}
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