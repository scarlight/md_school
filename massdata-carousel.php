<?php 

require_once('include/bfi_thumb/BFI_Thumb.php');

// Change the upload subdirectory
define( 'BFITHUMB_UPLOAD_DIR', 'carousel' );

// from advance custom field plugin
$carousel_items = get_field('massdata_carousel');

if(isset($carousel_items) && !empty($carousel_items))
{
    $carousel_items_set = explode(",", str_replace(" ","", $carousel_items));
    $thumb_img = "";
    $main_img = "";

    foreach ( $carousel_items_set as $image_items ) {

        $img_url = wp_get_attachment_image_src($image_items, "full"); //get URL from the first index
        $thumb_img .= massdata_generate_carousel_thumbnail($img_url, $image_items);
        $main_img .= massdata_generate_carousel_main_image($img_url, $image_items);

    }

    carousel_html($main_img, $thumb_img);
}

function massdata_generate_carousel_main_image($img_url, $image_items)
{
    $params_mainsize = array(
        'width' => 928,
        'height' => 433,
        'crop' => false
    );  

    //http://codex.wordpress.org/Function_Reference/wp_get_attachment_image_src
    if($img_url[1] == $params_mainsize['width'] && $img_url[2] == $params_mainsize['height']){
        return "<img id='main-".$image_items."' src='" . $img_url[0] . "' width='".$params_mainsize['width']."px' height='".$params_mainsize['height']."px' />";
    }
    else{
        return "<img id='main-".$image_items."' src='" . bfi_thumb($img_url[0] , $params_mainsize) . "' width='".$params_mainsize['width']."px' height='".$params_mainsize['height']."px' />";
    }

}

function massdata_generate_carousel_thumbnail($img_url, $image_items)
{
    $params_thumbnail = array(
        'width' => 90,
        'height' => 70,
        'crop' => true
    );

    return "<img id='thumb-".$image_items."' src='" . bfi_thumb($img_url[0] , $params_thumbnail) . "' width='".$params_thumbnail['width']."px' height='".$params_thumbnail['height']."px' />";
}

function carousel_html($main_img, $thumb_img){
    $carousel_html = '<div id="slider-home">';
    $carousel_html .= '<div id="massdata-slides">';
    $carousel_html .= $main_img;
    $carousel_html .= '</div>';
    $carousel_html .= '<div id="massdata-slides-thumbnail">';
    $carousel_html .= $thumb_img;
    $carousel_html .= '</div></div>';

    echo $carousel_html;
}