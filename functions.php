<?php

add_action( 'after_setup_theme', 'massdata_theme_setup' );
function massdata_theme_setup() 
{

    /* Define Constants */
    define('MASSDATA_THEMEROOT', get_template_directory_uri());
    define('MASSDATA_IMAGES', MASSDATA_THEMEROOT.'/images');
    define('WOOCOMMERCE_USE_CSS', false);

    $massdata_switch = false;
    define ('THEME_MASSDATA_CART'          , $massdata_switch);
    define ('THEME_MASSDATA_CHECKOUT'      , $massdata_switch);
    define ('THEME_MASSDATA_EMAILS'        , $massdata_switch);
    define ('THEME_MASSDATA_PLAIN'         , $massdata_switch);
    define ('THEME_MASSDATA_LOOP'          , $massdata_switch);
    define ('THEME_MASSDATA_MYACCOUNT'     , $massdata_switch);
    define ('THEME_MASSDATA_ORDER'         , $massdata_switch);
    define ('THEME_MASSDATA_SHOP'          , $massdata_switch);
    define ('THEME_MASSDATA_SINGLE_PRODUCT', $massdata_switch);
    define ('THEME_MASSDATA_ADD_TO_CART'   , $massdata_switch);
    define ('THEME_MASSDATA_TABS'          , $massdata_switch);
    define ('THEME_MASSDATA_WOOCOMMERCE'   , $massdata_switch);

    /* Add Theme option for this website */
    require_once ( get_template_directory() . '/theme-options.php' );
    //require_once ( get_template_directory() . '/quotation-info.php' );

    /* Fixes shortcode using wpautop that inserts additional p and br tag; */
    add_filter('the_content', 'shortcode_empty_paragraph_fix');

    /* Add "homepage" class to body when viewing home page */
    add_filter('body_class','massdata_homepage_add_class');

    /* Add Main Menus and Other Menus */
    add_action('init', 'massdata_register_my_menus');

    /* Load CSS Files */
    add_action('wp_enqueue_scripts', 'massdata_load_css_files');

    /* Load JS Files */
    add_action('wp_enqueue_scripts', 'massdata_load_js_files');

    /* Remove the admin bar from the front end */
    add_filter( 'show_admin_bar', '__return_false' );

    /* Customise the footer in admin area */
    add_filter('admin_footer_text', 'massdata_footer_admin');

    // Remove the version number of WP
    // Warning - this info is also available in the readme.html file in your root directory - delete this file!
    remove_action('wp_head', 'wp_generator');
    
    // Remove WooCommerce Generator
    add_action('woocommerce_init','massdata_remove_woocommerce_generator');

    /* Global custom function in admin area */
    //require_once ( get_template_directory() . '/global-custom-function.php' );

    /* Register our sidebars and widgetized areas. */
    add_action( 'widgets_init', 'massdata_widgets_init' );

    /* A shortcode for massdata FAQ */
    add_shortcode('md_faq', 'massdata_faq_shortcode');

    /* A shortcode for massdata FAQ's question and answer */
    add_shortcode('md_answer', 'massdata_faq_answer_shortcode');

    /* A shortcode for massdata event carousel */
    add_shortcode('md_slider', 'massdata_slider_shortcode');

    /* A shortcode for massdata event images carousel */
    add_shortcode('md_img', 'massdata_slider_image_shortcode');

    /* Woocommerce Support */
    add_theme_support('woocommerce');

    /* Remove woocommerce content wrapping */
    remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
    remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

    /* Remove woocommerce default single product tabbing and then add massdata request quote tab infront of it */
    remove_filter( 'woocommerce_product_tabs', 'woocommerce_default_product_tabs' );
    add_filter( 'woocommerce_product_tabs', 'massdata_default_product_tabs' );

    /* Remove woocommerce default catalog product empty image placeholder and use this instead */
    add_filter( 'woocommerce_placeholder_img_src', 'massdata_placeholder_img_src' );

    /* Modify Justin Tadlock breadcrumb trail on woocommerce single product page from showing "Product" > ... to actual Parent Category trailing */
    add_filter( 'breadcrumb_trail_items', 'massdata_breadcrumb_trail_items', 10, 2 );

    /* Display 32 products per page in the product category */
    add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 32;' ), 20 );

    /* the magical woocommerce autoloading template function, you cant find anywhere in woocommerce template folder because its a wordpress plugin filter to include template path in action */
    remove_filter( 'template_include', 'template_loader' );
    add_filter( 'template_include', 'massdata_template_loader', 11 ); // increase just once from the default 10

    /* to fix the permalink when product has more than one category assigned, its not perfect but will do for now */
    add_filter('post_link', 'massdata_product_permalink', 10, 3);
    add_filter('post_type_link', 'massdata_product_permalink', 10, 3);

}

/* Fixes shortcode using wpautop that inserts additional p and br tag; */
function shortcode_empty_paragraph_fix($content)
{   
    // array of custom shortcodes requiring the fix 
    $block = join("|",array("col", "md_faq", "md_answer", "md_slider", "md_img", "md_single_request_quote"));
    //EG: $block = join("|",array("col","shortcode2","shortcode3"));
 
    // opening tag
    $rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]",$content);
        
    // closing tag
    $rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/","[/$2]",$rep);
 
    return $rep;
}

/* Add "homepage" class to body when viewing home page */
function massdata_homepage_add_class() {
    
    if(is_front_page()){
        $classes[] = 'homepage';
    }
    else{
        $classes[] = '';   
    }
    return $classes;
}


/* Add Main Menus and Other Menus */
function massdata_register_my_menus() {
    register_nav_menus(array(
        'main-menu' => 'Main Menu',
        'popular-pendrive-categories-menu' => 'Popular Pen Drive Categories Menu',
        'designer-pendrive-categories-menu' => 'Designer Pen Drive Categories Menu',
        'current-stock-categories-menu' => 'Current Stock Categories Menu',
        'usb-gadget-categories-menu' => 'USB Gadget Categories Menu',
        'electronic-gadget-categories-menu' => 'Electronic Gadget Categories Menu'
    ));
}


/* Load CSS Files */
function massdata_load_css_files() {

    global $wp_styles, $is_IE;

    wp_register_style( 'massdata_fonts_googleapis', 'http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,700italic,400,600,700', array(), 'screen' );
    wp_register_style( 'massdata_css_bootstrap', MASSDATA_THEMEROOT.'/css/bootstrap.min.css', array(), '1.0', 'screen' );
    wp_register_style( 'massdata_css_theme', MASSDATA_THEMEROOT.'/css/theme.min.css', array('massdata_css_bootstrap'), '1.0', 'screen' );
    wp_register_style( 'massdata_css_template', MASSDATA_THEMEROOT.'/css/a_template.css', array('massdata_css_bootstrap'), '1.0', 'screen' );
    wp_register_style( 'massdata_css_superfish', MASSDATA_THEMEROOT.'/css/superfish.min.css', array(), '1.0', 'screen' );
    wp_register_style( 'massdata_font_awesome', MASSDATA_THEMEROOT.'/css/font-awesome.min.css', array(), '3.2.1', 'screen' );
    wp_register_style( 'massdata_font_awesome_ie', MASSDATA_THEMEROOT.'/css/font-awesome-ie7.min.css', array('massdata-font-awesome'), '3.2.1', 'screen' );

    wp_enqueue_style( 'massdata_fonts_googleapis');
    wp_enqueue_style( 'massdata_css_bootstrap');
    wp_enqueue_style( 'massdata_css_theme');
    wp_enqueue_style( 'massdata_css_template');
    wp_enqueue_style( 'massdata_css_superfish');
    wp_enqueue_style( 'massdata_font_awesome');

    if ( $is_IE ) {
        wp_enqueue_style( 'massdata_font_awesome_ie');
        // Add IE conditional tags for IE 7 and older
        $wp_styles->add_data( 'massdata_font_awesome_ie', 'conditional', 'lte IE 7' );
    }
}

/* Load JS Files */
function massdata_load_js_files() {

  wp_register_script('massdata_js_bootstrap', MASSDATA_THEMEROOT.'/js/bootstrap.min.js', array('jquery'), '1.0', false );
  wp_register_script('massdata_js_easing', MASSDATA_THEMEROOT.'/js/jquery.easing.1.3.js', array(), '1.3', false );
  wp_register_script('massdata_js_validate', MASSDATA_THEMEROOT.'/js/jquery.validate.js', array(), '1.11.1', false );
  wp_register_script('massdata_js_caroufredsel', MASSDATA_THEMEROOT.'/js/jquery.carouFredSel.js', array('jquery'), '6.1.0', false );
  wp_register_script('massdata_js_tweenmax', MASSDATA_THEMEROOT.'/js/TweenMax.min.js', array('jquery'), '1.10.3', false );
  wp_register_script('massdata_js_supersubs', MASSDATA_THEMEROOT.'/js/supersubs.js', array('jquery'), 'v0.3b', false );
  wp_register_script('massdata_js_superfish', MASSDATA_THEMEROOT.'/js/superfish.min.js', array('jquery'), 'v1.7.4', false );
  wp_register_script('massdata_js_custom', MASSDATA_THEMEROOT.'/js/custom.min.js', array('jquery'), '1.0', false );

  wp_enqueue_script('massdata_js_bootstrap');
  wp_enqueue_script('massdata_js_easing');
  wp_enqueue_script('massdata_js_validate');
  wp_enqueue_script('massdata_js_caroufredsel');
  wp_enqueue_script('massdata_js_tweenmax');
  wp_enqueue_script('massdata_js_supersubs');
  wp_enqueue_script('massdata_js_superfish');
  wp_enqueue_script('massdata_js_custom');
}

/* Customise the footer in admin area */
function massdata_footer_admin () {
  echo 'Theme designed and developed by <a href="http://www.richcodesign.com/" target="_blank">Rich Codesign</a> and powered by <a href="http://wordpress.org" target="_blank">WordPress</a>.';
}

/* Register our sidebars and widgetized areas. */
function massdata_widgets_init() {
    if(function_exists("register_sidebar"))
    {
        register_sidebar( array(
            'name'          => __( 'Right Sidebar' ),
            //'id'            => 'right_sidebar',
            'description'   => 'Widgets in this area will be shown on the right-hand side.',
            'class'         => '',
            'before_widget' => '<li id="%1$s" class="widget %2$s">',
            'after_widget'  => '</li>',
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => '</h2>'
        ));
    }
}

/* A shortcode for massdata FAQ */
function massdata_faq_shortcode($atts, $content){
    $atts = shortcode_atts(
        array(
            'content' => !empty($content) ? $content : NULL
        ), $atts
    );

    extract($atts);

    if(!empty($content)){
        return '<ol class="faq">'.do_shortcode($content).'</ol>';
    }
    
    return "";
}

/* A shortcode for massdata FAQ's question and answer */
function massdata_faq_answer_shortcode($atts, $content){
    $atts = shortcode_atts(
        array(
            'faq' => "Question",
            'content' => !empty($content) ? $content : NULL
        ), $atts
    );

    extract($atts);

    if(!empty($content)){
        return "<li>"."<h6>$faq</h6>"."<p>$content</p>"."</li>";
    }

    return "";
}

/* A shortcode for massdata event carousel */
function massdata_slider_shortcode($atts, $content){
    $atts = shortcode_atts(
        array(
            'content' => !empty($content) ? $content : NULL
        ), $atts
    );

    extract($atts);

    if(!empty($content)){
        $concatenate  = '<div class="event-buttons"><a class="prev"></a><a class="next"></a></div>';
        $concatenate .= '<ul class="event-thumbnail">'.do_shortcode($content).'</ul>';
        return $concatenate;
    }

    return "";
}

/* A shortcode for massdata event image carousel */
function massdata_slider_image_shortcode($atts, $content){
    $atts = shortcode_atts(
        array(
            'item' => 0,
            'title' => "",
            'group' => "",
            'content' => !empty($content) ? $content : NULL
        ), $atts
    );

    extract($atts);
    require_once('include/bfi_thumb/BFI_Thumb.php');

    $item = absint($item);
    if(isset($item) && !empty($item) ){
        $img_url = wp_get_attachment_image_src($item, "full");
        $params_thumbnail = array(
            'width' => 130,
            'height' => 100,
            'crop' => true
        );

        $img_url_thumbnail = bfi_thumb($img_url[0] , $params_thumbnail);      

        if(isset($group) && !empty($group)){
            $group = "lightbox"."-".$group;
        }
        else{
            $group = "lightbox";
        }      
    
        $concatenate  = '<li><a href="'.$img_url[0].'" title="'.$title.'" rel="'.$group.'">';
        $concatenate .= '<img alt="'.$title.'" src="'.$img_url_thumbnail.'"'.' width="'.$params_thumbnail['width'].'px" height="'.$params_thumbnail['height'].'px" />';
        $concatenate .= '</a></li>';
        
        return $concatenate;
    }

    return "";

}

// Remove WooCommerce Generator
function massdata_remove_woocommerce_generator() {
    global $woocommerce;
    remove_action( 'wp_head', array( $woocommerce, 'generator' ) );
}


/* Remove woocommerce default single product tabbing and add massdata request quote tab together */
function massdata_default_product_tabs( $tabs = array() ) {
    global $product, $post, $MASSDATA_CURRENT_TEMPLATE_FILE;

    if( $MASSDATA_CURRENT_TEMPLATE_FILE == "single-product-currentstock.php" ) {

        $tabs['stock_detail'] = array(
            'title'    => __( 'Stock Details', 'woocommerce' ),
            'priority' => 1,
            'callback' => 'massdata_locate_template_stock_detail'
        );

    } else {
        // Request Quote tab - to request quote for the current single product
        $tabs['request_quote'] = array(
            'title'    => __( 'Request Quote', 'woocommerce' ),
            'priority' => 1,
            'callback' => 'massdata_locate_template_request_quote'
        );
    }

    // Description tab - shows product content
    if ( $post->post_content )
        $tabs['description'] = array(
            'title'    => __( 'Description', 'woocommerce' ),
            'priority' => 10,
            'callback' => 'woocommerce_product_description_tab'
        );

    // Additional information tab - shows attributes
    if ( $product->has_attributes() || ( get_option( 'woocommerce_enable_dimension_product_attributes' ) == 'yes' && ( $product->has_dimensions() || $product->has_weight() ) ) )
        $tabs['additional_information'] = array(
            'title'    => __( 'Additional Information', 'woocommerce' ),
            'priority' => 20,
            'callback' => 'woocommerce_product_additional_information_tab'
        );

    // Reviews tab - shows comments
    if ( comments_open() )
        $tabs['reviews'] = array(
            'title'    => sprintf( __( 'Reviews (%d)', 'woocommerce' ), get_comments_number( $post->ID ) ),
            'priority' => 30,
            'callback' => 'comments_template'
        );

    return $tabs;
}

function massdata_locate_template_stock_detail(){
    locate_template('md_reservation_form.php', true, true);
}

function massdata_locate_template_request_quote(){
    locate_template('md_request_quotation.php', true, true);
}

/* Remove woocommerce default catalog product empty image placeholder and use this instead */
function massdata_placeholder_img_src() {
    return MASSDATA_IMAGES.'/no-preview-catalog.gif';
}

/* Modify Justin Tadlock breadcrumb trail on woocommerce single product page from showing "Product" > ... to actual Parent Category trailing */
add_filter( 'breadcrumb_trail_items', 'massdata_breadcrumb_trail_items', 10, 2 );
function massdata_breadcrumb_trail_items( $items, $args ){

    global $MASSDATA_CURRENT_TEMPLATE_FILE;
    // echo "<pre>";
    //     print_r(get_queried_object());
    //     print_r(get_post_meta(get_queried_object_id()));
    //     print_r(get_post_type());
    // echo "</pre>";

    // return back without changing the data
    if(is_product_category()){
        return $items;
    }
    elseif(is_shop()){
        $items = array_splice( $items, 1, 0 );
        $items[] = "All Categories";
        return $items;
    }
    elseif ( is_single() && !is_attachment() ) {

        if ( get_post_type() == 'product' ) {

            $items = array_splice( $items, 1, 0 );

            if ( $terms = wc_get_product_terms( get_queried_object_id(), 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {

                $category_slugs = array(
                    "popular-pen-drive-current-stock",
                    "designer-pen-drive-current-stock",
                    "usb-gadget-current-stock",
                    "electronic-gadget-current-stock"
                );
                
                if ( $MASSDATA_CURRENT_TEMPLATE_FILE == 'single-product-currentstock.php' ) {

                    foreach ( $terms as $value ) {

                        $terms_index = 0;                        

                        if( in_array ( strtolower( $value->slug ) , $category_slugs ) ) {

                            $main_term = $terms[$terms_index];

                            break;

                        }

                        $terms_index++;

                    }

                } else {

                    foreach ( $terms as $value ) {

                        $terms_index = 0;

                        if( in_array ( strtolower( $value->slug ) , $category_slugs ) ) {

                            unset($terms[$terms_index]);

                        }

                        $terms_index++;

                    }

                    $terms = array_values($terms);
                    
                    $main_term = $terms[0];

                }
                
                $ancestors = get_ancestors( $main_term->term_id, 'product_cat' );

                $ancestors = array_reverse( $ancestors );

                foreach ( $ancestors as $ancestor ) {
                    $ancestor = get_term( $ancestor, 'product_cat' );

                    if ( ! is_wp_error( $ancestor ) && $ancestor )
                        $items[] = '<a href="' . get_term_link( $ancestor->slug, 'product_cat' ) . '">' . $ancestor->name . '</a>';
                }

                $items[] = '<a href="' . get_term_link( $main_term->slug, 'product_cat' ) . '">' . $main_term->name . '</a>';

            }

            $items[] = get_the_title();

            return $items;

        }
    }
    // copy and modify from line 115 shop/breadcrumb.php with get_queried_object_id()
    else {
        $items = array_splice( $items, 1, 0 );

        if ( $terms = wc_get_product_terms( get_queried_object_id(), 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {

            $main_term = $terms[0];

            $ancestors = get_ancestors( $main_term->term_id, 'product_cat' );

            $ancestors = array_reverse( $ancestors );

            foreach ( $ancestors as $ancestor ) {
                $ancestor = get_term( $ancestor, 'product_cat' );

                if ( ! is_wp_error( $ancestor ) && $ancestor )
                    $items[] = '<a href="' . get_term_link( $ancestor->slug, 'product_cat' ) . '">' . $ancestor->name . '</a>';
            }

            $items[] = '<a href="' . get_term_link( $main_term->slug, 'product_cat' ) . '">' . $main_term->name . '</a>';

        }

        $items[] = get_the_title();

        return $items;
    }
            
}

/* the magical woocommerce autoloading template function, you cant find anywhere in woocommerce template folder because its a wordpress plugin filter to include template path in action */
function massdata_template_loader( $template ) {
    global $woocommerce;
    
    $find = array( 'woocommerce.php' );
    $file = ''; 

    if ( is_single() && get_post_type() == 'product' ) {

        $category_slug = get_query_var( 'product_cat' );

        $cat_slugs = array(
            "popular-pen-drive-current-stock",
            "designer-pen-drive-current-stock",
            "usb-gadget-current-stock",
            "electronic-gadget-current-stock"
        );

        $currentstock_cat = false;

        foreach ($cat_slugs as $value) {

            if($category_slug == $value) {
                $currentstock_cat = true;
            }

        }
        
        if( $currentstock_cat ) {
            
            $file   = 'single-product-currentstock.php';
            $find[] = $file;
            $find[] = $woocommerce->template_url . $file;

        } else {

            $file   = 'single-product.php';
            $find[] = $file;
            $find[] = $woocommerce->template_url . $file;

        }

    } elseif ( is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) {
        
        $term = get_queried_object(); // can help us to find categories
        $term_parent_obj = get_term_by('id', $term->parent, "product_cat");
        
        if( $term->term_id == "16" || $term->slug == "current-stock" ){ // for current stock

            $file       = 'taxonomy-product_cat-currentstock.php';
            $find[]     = $file;
            $find[]     = $woocommerce->template_url . $file;

        } elseif ( $term_parent_obj ) {

            if( $term_parent_obj->term_id == "16" || $term_parent_obj->slug == "current-stock" ){ // for current stock subcategories

                $file       = 'taxonomy-product_cat-currentstock.php';
                $find[]     = $file;
                $find[]     = $woocommerce->template_url . $file;

            }

        } else { // woocommerce default continued here

            $file       = 'taxonomy-' . $term->taxonomy . '.php';
            $find[]     = 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
            $find[]     = $woocommerce->template_url . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
            $find[]     = $file;
            $find[]     = $woocommerce->template_url . $file;

        }

    } elseif ( is_post_type_archive( 'product' ) || is_page( woocommerce_get_page_id( 'shop' ) ) ) {

        $file   = 'archive-product.php';
        $find[] = $file;
        $find[] = $woocommerce->template_url . $file;

    }

    if ( $file ) {
        $template = locate_template( $find );
        if ( ! $template ) $template = $woocommerce->plugin_path() . '/templates/' . $file;
    }

    global $MASSDATA_CURRENT_TEMPLATE_FILE;
    if ( empty($MASSDATA_CURRENT_TEMPLATE_FILE) || !isset($MASSDATA_CURRENT_TEMPLATE_FILE) ) {

        $MASSDATA_CURRENT_TEMPLATE_FILE = basename($template);

    }

    return $template;
}

/* to fix the permalink when product has more than one category assigned, its not perfect but will do for now */
function massdata_product_permalink($permalink, $post_id, $leavename){

    global $MASSDATA_CURRENT_TEMPLATE_FILE;

    $current_stock_cat = array (
        "popular-pen-drive-current-stock",
        "designer-pen-drive-current-stock",
        "usb-gadget-current-stock",
        "electronic-gadget-current-stock"
    );
        
    if( ( 'product' == get_post_type( $post_id ) && $MASSDATA_CURRENT_TEMPLATE_FILE == "taxonomy-product_cat.php" ) || is_shop() ) {

        $linked_categories = wp_get_post_terms( $post_id->ID, 'product_cat', array('fields' => 'all')); //get the categories attached to this product

        if( count($linked_categories) > 1 ) { //more than 1 category is assigned

            $linked_categories = wp_list_pluck( $linked_categories, 'slug');

            $product_cat_slug_to_remove = "";

            foreach ( $linked_categories as $key => $value ) {

                if( in_array ( strtolower( $value ), $current_stock_cat ) ) {

                    $product_cat_slug_to_remove = $value;
                    unset( $linked_categories[$key] );

                }

            }

            $linked_categories = array_values($linked_categories);              

            $permalink = str_replace($product_cat_slug_to_remove, $linked_categories[0], $permalink); // the current stock category has been poped and if there is still more than one category, just get the 0 indexed value - dont care it.

            return $permalink;

        } else {

            return $permalink;

        }

    } 
    elseif ( 'product' == get_post_type( $post_id ) && $MASSDATA_CURRENT_TEMPLATE_FILE == "taxonomy-product_cat-currentstock.php" ) {

        $linked_categories = wp_get_post_terms( $post_id->ID, 'product_cat', array('fields' => 'all')); //get the categories attached to this product            

        if( count($linked_categories) > 1 ) { //more than 1 category is assigned

            $linked_categories = wp_list_pluck( $linked_categories, 'slug');
            
            $product_cat_slug_to_keep = "";

            foreach ( $current_stock_cat as $value ) {

                if( in_array ( strtolower( $value ), $linked_categories ) ) {

                    $product_cat_slug_to_keep = $value;

                }

            }

            if( preg_match_all( "/\/$product_cat_slug_to_keep\//i" , $permalink ) ) { //if the link is already has the current stock category, return

                return $permalink;

            } else {

                foreach ( $linked_categories as $key => $value ) { //loop just in case got other categories beside the current stock categories. EG: cat1, cat2, ...
                    
                    if ( preg_match_all( "/\/$value\//i" , $permalink ) ) {

                        $permalink = str_replace( $value, $product_cat_slug_to_keep, $permalink ); // apply the current stock category string into permalink.        

                        return $permalink;

                    }

                }

            }

        }

    }
    else {

        return $permalink;

    }
    
}



////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////// tOUch tHE cODE bELOW aND tHE uNIVERSE wILL eXPLODE /////////
////////////////////////////////////////////////////////////////////////////////////////////


add_action('register_post', 'massdata_register_post', 10, 3);
add_filter('registration_errors', 'massdata_register_error', 10, 3);
add_action('user_register', 'massdata_user_register');
function massdata_register_post($sanitized_user_login, $user_email, $errors)
{


    if (is_user_logged_in()) {
        exit(wp_redirect(site_url()));
    }
    $refer = strstr($_SERVER['HTTP_REFERER'], '?status', true);
    if (!empty($refer)) {
        $_SERVER['HTTP_REFERER'] = $refer;
    }

    $sanitized_user_login = sanitize_text_field($_POST['user_login']);
    $user_email = sanitize_text_field($_POST['user_email']);

    if (isset($_POST['md-signup']) && $_POST['md-signup'] == 'Submit') {
        $url_status_failed = add_query_arg(array(urlencode('status') => urlencode('signup')), $_SERVER['HTTP_REFERER']);
    } else if (isset($_POST['md-submit']) && $_POST['md-submit'] == 'Submit') {
        $url_status_failed = add_query_arg(array(urlencode('status') => urlencode('submit')), $_SERVER['HTTP_REFERER']);
    }

    if (empty($sanitized_user_login)) {
        $errors->add('error', '<strong>ERROR</strong>: You must include your full name.');
        exit(wp_redirect(add_query_arg(array(urlencode('message') => urlencode('You must include your full name')), $url_status_failed)));
    } else {

        if (!validate_username($sanitized_user_login)) {

            $errors->add('error', '<strong>ERROR</strong>: You must include a valid full name.');
            wp_redirect(add_query_arg(array(urlencode('message') => urlencode('You must include a valid full name')), $url_status_failed));
            exit;

        } else if (strlen($sanitized_user_login) >= 50) {

            $errors->add('error', '<strong>ERROR</strong>: Username must be within 50 characters.');
            exit(wp_redirect(add_query_arg(array(urlencode('message') => urlencode('Username must be within 50 characters.')), $url_status_failed)));

        } else if (username_exists($sanitized_user_login)) {

            $errors->add('error', '<strong>ERROR</strong>: This username exist. Please enter a different username.');

            if (isset($_POST['md-signup']) && $_POST['md-signup'] == 'Submit') {
                exit(wp_redirect(add_query_arg(array(urlencode('message') => urlencode('This username exist. Please enter a different username')), $url_status_failed)));
            } else if (isset($_POST['md-submit']) && $_POST['md-submit'] == 'Submit') {
                exit(wp_redirect(add_query_arg(array(urlencode('message') => urlencode('This username exist. Please login to send quote if you are registered in the system')), $url_status_failed)));
            }

        }
    }

    if (empty($user_email)) {

        $errors->add('error', '<strong>ERROR</strong>: You must include your email address.');
        exit(wp_redirect(add_query_arg(array(urlencode('message') => urlencode('You must include your email address')), $url_status_failed)));

    } else {

        if (email_exists($user_email)) {

            $errors->add('error', '<strong>ERROR</strong>: This email is registered in the system. Please use another email address to register.');
            if (isset($_POST['md-signup']) && $_POST['md-signup'] == 'Submit') {
                exit(wp_redirect(add_query_arg(array(urlencode('message') => urlencode('This email is registered in the system')), $url_status_failed)));
            } else if (isset($_POST['md-submit']) && $_POST['md-submit'] == 'Submit') {
                exit(wp_redirect(add_query_arg(array(urlencode('message') => urlencode('This email is registered in the system. Please login to send quote if you are registered in the system')), $url_status_failed)));
            }

        } else if (!is_email($user_email)) {

            $errors->add('error', '<strong>ERROR</strong>: Please enter a valid email address');
            exit(wp_redirect(add_query_arg(array(urlencode('message') => urlencode('Please enter a valid email address')), $url_status_failed)));

        } else if (strlen($user_email) > 100) {

            $errors->add('error', '<strong>ERROR</strong>: Your email address is too long. Valid email address length within 100 characters.');
            exit(wp_redirect(add_query_arg(array(urlencode('message') => urlencode('Your email address is too long. Valid email address length is within 100 characters')), $url_status_failed)));
        }
    }

    return $errors;
}
function massdata_register_error($errors, $sanitized_user_login, $user_email){

    require_once(get_template_directory() . '/md_validator.php');

    $refer = strstr($_SERVER['HTTP_REFERER'], '?', true);
    if (!empty($refer)) {
        $_SERVER['HTTP_REFERER'] = $refer;
    }

    if (isset($_POST['md-signup']) && $_POST['md-signup'] == 'Submit') {

        $url_status_failed = add_query_arg(array(urlencode('status') => urlencode('signup')), $_SERVER['HTTP_REFERER']);

        $productquote_massdata_quotation_message = null;
        $productquote_errors = signup_validator();

        foreach ($errors as $error => $fields) {
            foreach ($fields as $field => $message) {
                if (!empty($message)) {
                    $product_quote_massdata_quotation_message = $message[0];
                }
            }
            if (!is_null($productquote_massdata_quotation_message)) {
                break;
            }
        }
        if (!is_null($productquote_massdata_quotation_message)) {
            exit(wp_redirect(add_query_arg(array(urlencode('message') => urlencode($productquote_massdata_quotation_message)), $url_status_failed)));
        }

    } else if (isset($_POST['md-submit']) && $_POST['md-submit'] == 'Submit') {

        $url_status_failed = add_query_arg(array(urlencode('status') => urlencode('submit')), $_SERVER['HTTP_REFERER']);

        $productquote_massdata_quotation_message = null;
        $productquote_errors = signup_validator();
        $productquote_errors = quote_validator();

        foreach ($errors as $error => $fields) {
            foreach ($fields as $field => $message) {
                if (!empty($message)) {
                    $product_quote_massdata_quotation_message = $message[0];
                }
            }
            if (!is_null($productquote_massdata_quotation_message)) {
                break;
            }
        }
        if (!is_null($productquote_massdata_quotation_message)) {
            exit(wp_redirect(add_query_arg(array(urlencode('message') => urlencode($productquote_massdata_quotation_message)), $url_status_failed)));
        }
    }

    return $errors;
}
function massdata_user_register($user_id, $password = "", $meta = array())
{

    require_once get_template_directory() . '/md_post_data.php';

    $post_error = array();
    if (isset($_POST['md-signup']) && $_POST['md-signup'] === 'Submit') {

        $url_status_failed = add_query_arg(array(urlencode('status') => urlencode('signup')), $_SERVER['HTTP_REFERER']);

        $post_error = send_user_data($user_id);

        if (empty($post_error)) {
            exit(wp_safe_redirect(home_url()));
        } else {
            exit(wp_redirect(add_query_arg(array(urlencode('message') => urlencode($post_error[0])), $url_status_failed)));
        }
    } else if (isset($_POST['md-submit']) && $_POST['md-submit'] === 'Submit') {

        $url_status_failed = add_query_arg(array(urlencode('status') => urlencode('submit')), $_SERVER['HTTP_REFERER']);

        if (isset($_POST['__md__']) && isset($_POST['md-in-product'])) {

            $post_error = send_user_data($user_id);
            // check hidden field for product id, and also verify product id exist in the system.
            $post_error = send_quote($_POST['__md__']);
            if (empty($post_error)) {
                exit(wp_redirect(add_query_arg(array(urlencode('message') => urlencode("Quotation is successfully sent")), $url_status_failed)));
            } else {
                exit(wp_redirect(add_query_arg(array(urlencode('message') => urlencode($post_error[0])), $url_status_failed)));
            }

        } else {

            exit(wp_redirect(add_query_arg(array(urlencode('message') => urlencode("An error has occured. Please reopen tab, and try again")), $url_status_failed)));
        }
    }

}

add_action('wp_login_failed', 'massdata_login_failed');
add_filter('login_redirect', 'massdata_login_redirect', 10, 3);
add_filter('authenticate', 'massdata_authenticate_username_password', 15, 3); //default of wordpress core, default is 20
function massdata_login_failed($username)
{
    $referrer = $_SERVER['HTTP_REFERER'];
    if (!empty($referrer) && !strstr($referrer, 'wp-login') && !strstr($referrer, 'wp-admin')) {
        $url = add_query_arg(urlencode('status'), urlencode('signin'), $_SERVER['HTTP_REFERER']);
        exit(wp_redirect(add_query_arg(urlencode('message'), urlencode('Login failed. Try again. Reset your password if you have forgotten it.'), $url)));
    }
}
function massdata_login_redirect($redirect_to, $request, $user)
{
    global $user;
    if (isset($user->roles) && is_array($user->roles)) {
        //check for admins
        if (in_array("administrator", $user->roles)) {
            //$redirect suppose to have default url but empty, so setting manually
            return admin_url();
        } else {
            return site_url();
        }
    }
}
function massdata_authenticate_username_password($user, $username, $password){
    if (is_a($user, 'WP_User')) {
        return $user;
    }

    if (isset($_SERVER['HTTP_REFERER'])) {
        $referer = $_SERVER['HTTP_REFERER'];
    }
    if (!empty($referer) && !strstr($referer, 'wp-login') && !strstr($referer, 'wp-admin')) {
        if (empty($username)) {
            $url = add_query_arg(urlencode('status'), urlencode('signin'), $referer);
            exit(wp_redirect(add_query_arg(urlencode('message'), urlencode('Login failed, asd Try again. Reset your password if you have forgotten it.'), $url)));
        } else if (empty($password)) {
            $url = add_query_arg(urlencode('status'), urlencode('signin'), $referer);
            exit(wp_redirect(add_query_arg(urlencode('message'), urlencode('Login failed. asd Try again. Reset your password if you have forgotten it.'), $url)));
        }
    }
    if (empty($username) || empty($password)) {
        if (is_wp_error($user))
            return $user;

        $error = new WP_Error();
        if (empty($username))
            $error->add('empty_username', __('<strong>ERROR</strong>: The username field is empty.'));
        if (empty($password))
            $error->add('empty_password', __('<strong>ERROR</strong>: The password field is empty.'));

        return $error;
    }

    $user = get_user_by('login', $username);
    if (!$user)
        return new WP_Error('invalid_username', sprintf(__('<strong>ERROR</strong>: Invalid username. <a href="%s" title="Password Lost and Found">Lost your password</a>?'), wp_lostpassword_url()));

    $user = apply_filters('wp_authenticate_user', $user, $password);
    if (is_wp_error($user))
        return $user;

    if (!wp_check_password($password, $user->user_pass, $user->ID))
        return new WP_Error('incorrect_password', sprintf(__('<strong>ERROR</strong>: The password you entered for the username <strong>%1$s</strong> is incorrect. <a href="%2$s" title="Password Lost and Found">Lost your password</a>?'),
            $username, wp_lostpassword_url()));

    return $user;
}

add_action('init', 'register_massdata_post_type');
add_action('admin_init', 'massdata_quotation_posttype_metaboxes');
add_action('admin_init', 'massdata_reservation_posttype_metaboxes');
function register_massdata_post_type()
{
    $label_quotation = array(
        'name' => 'Quotations',
        'singular_name' => 'Quotation',
        'menu_name' => null,
        'all_items' => "Product Quotation",
        'add_new' => null,
        'add_new_item' => null,
        'edit_item' => null,
        'new_item' => null,
        'view_item' => 'View Quotations',
        'search_item' => 'Search Quotations',
        'not_found' => 'No quotations found',
        'not_found_in_trash' => 'No quotation found in Trash',
        'parent_item_colon' => '',
        'menu_name' => 'Mass Data'
    );
    $args_quotation = array(
        'labels' => $label_quotation,
        'public' => false,
        'publicly_querable' => false,
        'show_ui' => true,
        'show_in_menu' => 'edit.php?post_type=massdata_reserve',
        'query_var' => true,
        'rewrite' => array(
            'front-write' => '',
            'slug' => 'quotes'
        ),
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title')
    );
    $label_reservation = array(
        'name' => 'Reservations',
        'singular_name' => 'Reservation',
        'menu_name' => null,
        'all_items' => "Product Reservation",
        'add_new' => null,
        'add_new_item' => null,
        'edit_item' => null,
        'new_item' => null,
        'view_item' => 'View Reservation',
        'search_item' => 'Search Reservations',
        'not_found' => 'No reservations found',
        'not_found_in_trash' => 'No reservation found in Trash',
        'parent_item_colon' => '',
        'menu_name' => 'Mass Data'
    );
    $args_reservation = array(
        'labels' => $label_reservation,
        'public' => false,
        'publicly_querable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array(
            'front-write' => '',
            'slug' => 'reserve'
        ),
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title')
    );
    register_post_type('massdata_quotation', $args_quotation);
    register_post_type('massdata_reserve', $args_reservation);
}
function massdata_quotation_posttype_metaboxes()
{
    add_meta_box(
        'quotation_meta_box', // HTML id attribute
        'Quote Information', // Text visible in the heading
        'display_quotation_meta_box', // Function to spit the html
        'massdata_quotation', //custom post type to display
        'normal',
        'high'
    );

    function display_quotation_meta_box()
    {
        require_once(get_template_directory() . '/md_quotation_info.php');
        require_once(get_template_directory() . '/md_quotation_user_info.php');
    }
}
function massdata_reservation_posttype_metaboxes()
{
    add_meta_box(
        'reservation_meta_box', // HTML id attribute
        'Product Reservation', // Text visible in the heading
        'display_reservation_meta_box', // Function to spit the html
        'massdata_reserve', //custom post type to display
        'normal',
        'high'
    );
    function display_reservation_meta_box()
    {
        require_once(get_template_directory() . '/md_reservation_info.php');
    }
}

add_action('add_meta_boxes', 'massdata_remove_post_boxes');
function massdata_remove_post_boxes(){
    remove_meta_box('submitdiv', 'massdata_quotation', 'high');
    remove_meta_box('submitdiv', 'massdata_reserve', 'high');
}


add_action( 'woocommerce_product_options_general_product_data', 'massdata_add_custom_total_stock_field' );
//add_action( 'woocommerce_process_product_meta', 'massdata_add_custom_total_stock_field_save' );
//add_action( 'woocommerce_product_after_variable_attributes_js', 'variable_fields_js' );
add_action( 'woocommerce_process_product_meta_variable', 'variable_fields_process', 10, 1 );
add_action( 'woocommerce_product_after_variable_attributes', 'variable_fields', 9, 2 );
function massdata_add_custom_total_stock_field(){
    global $woocommerce, $post, $product;

    $total_stock_count = get_post_meta($post->ID, '_variant_total_stock', true);
    if(!is_numeric($total_stock_count)){
        $html= "<p class=\"form-field product_field_type\"><label for=\"product_field_type\">Total Variant Stock: 0 </label></p>";
        echo $html;
    }else{
        $html= "<p class=\"form-field product_field_type\"><label for=\"product_field_type\">Total Variant Stock: {$total_stock_count}</label></p>";
        echo $html;
    }
}
function variable_fields( $loop, $variation_data ) {
    ?>
    <tr>
        <td>
            <div>
                <label>Quantity (Price per Quantity)</label>
                <input type="number" size="5" name="_massdata_quantity[<?php echo $loop; ?>]" value="<?php if(isset($variation_data['_massdata_quantity'][0])){ echo $variation_data['_massdata_quantity'][0]; }?>"/>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div>
                <label>Agent's Price</label>
                <input type="number" size="5" name="_massdata_agent_price[<?php echo $loop; ?>]" value="<?php if(isset($variation_data['_massdata_agent_price'][0])){ echo $variation_data['_massdata_agent_price'][0]; }?>"/>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div>
                <label>Corporate's Price</label>
                <input type="number" size="5" name="_massdata_corporate_price[<?php echo $loop; ?>]" value="<?php if(isset($variation_data['_massdata_corporate_price'][0])){ echo $variation_data['_massdata_corporate_price'][0]; }?>"/>
            </div>
        </td>
    </tr>
<?php
}
function variable_fields_process( $post_id ) {
    if (isset( $_POST['variable_sku'] ) ) :

        global $post;
        $variable_sku = $_POST['variable_sku'];
        $variable_post_id = $_POST['variable_post_id'];

        $variable_agent_price = $_POST['_massdata_agent_price'];
        $variable_corporate_price = $_POST['_massdata_corporate_price'];
        $variable_quantity = $_POST['_massdata_quantity'];

        $current_stock = get_post_meta($post_id, '_variant_total_stock', true);
        $total_stock = 0;
        for ( $i = 0; $i < sizeof( $variable_sku ); $i++ ) :

            $variation_id = (int)$variable_post_id[$i];
            if( isset( $variable_corporate_price[$i] ) ){
                update_post_meta( $variation_id, '_massdata_corporate_price', stripslashes( $variable_corporate_price[$i] ) );
            }
            if ( isset( $variable_agent_price[$i] ) ) {
                update_post_meta( $variation_id, '_massdata_agent_price', stripslashes( $variable_agent_price[$i] ) );
            }
            if ( isset( $variable_quantity[$i] ) ) {
                update_post_meta( $variation_id, '_massdata_quantity', stripslashes( $variable_quantity[$i] ) );
            }

            $total_stock = $total_stock + get_post_meta($variation_id, '_stock', true);
        endfor;


        update_post_meta($post_id, '_variant_total_stock', $total_stock);
    endif;

}

add_filter('manage_edit-massdata_reserve_columns', 'edit_massdata_reserve_columns');
add_action('manage_massdata_reserve_posts_custom_column', 'edit_massdata_reserve_column_data', 10, 2);
add_filter('manage_edit-massdata_quotation_columns', 'edit_massdata_quotation_columns');
add_action('manage_massdata_quotation_posts_custom_column', 'edit_massdata_quotation_column_data');
//add_filter('post_row_actions', 'quotation_remove_row_actions', 10, 2);
//add_filter('bulk_actions-edit-massdata_quotation', 'quotation_remove_bulk_actions');
function edit_massdata_quotation_columns($column_headers)
{
    unset($column_headers);
    $column_headers['cb'] = '<input type="checkbox"/>';
    $column_headers['md_title'] = 'Quote ID';
    $column_headers['massdata_quote_user'] = 'User';
    $column_headers['massdata_products'] = 'Products';
    $column_headers['massdata_quantity'] = 'Quantity';
    $column_headers['date'] = 'Order Date';

    return $column_headers;
}
function edit_massdata_quotation_column_data($column_headers)
{
    $quote_object = get_post_type_object('massdata_quotation');
    $quote_request = array(
        'post_type' => 'massdata_quotation'
    );
    $quote_query = new Wp_Query($quote_request);

    global $post;

    switch ($column_headers):
        case 'md_title':

            echo "<a href=\"" . admin_url() . "post.php?post={$post->ID}&action=edit\">{$post->post_title}</a>";
            break;
        case 'massdata_quote_user':

            $user = get_userdata($post->post_author);
            echo $user->data->user_login;
            break;
        case 'massdata_products':

            echo $post->post_content;
            break;
        case 'massdata_quantity':

            echo get_post_meta($post->ID, 'md_in_quantity', true);
            break;
        case 'date':

            echo $post->post_date_gmt;
            break;
        default:
            break;
    endswitch;


}
function edit_massdata_reserve_columns($column_headers){

    $temp = $column_headers['cb'];
    unset($column_headers);
    $column_headers['cb'] = $temp;
    $column_headers['md_title'] = 'Reservation ID';
    $column_headers['massdata_reserved_product'] = 'Product ID';
    $column_headers['massdata_reserved_stock'] ="Stock Reserved";
    $column_headers['massdata_reserver'] = 'User ID';
    $column_headers['reservation_date'] = 'Reserve Date';

    return $column_headers;
}
function edit_massdata_reserve_column_data($column_headers, $post_id){

    global $post;
    switch($column_headers):
        case 'md_title':
//            wp-admin/post.php?post=513&action=edit
            echo "<a href=\"" . admin_url() . "post.php?post={$post->ID}&action=edit\">Reservation#{$post->ID}</a>";
            break;
        case 'massdata_reserved_product':

            echo $post->post_content;
            break;
        case 'massdata_reserved_stock':

            echo '<pre>';
            $reserved_product = get_post_meta($post->ID);
            //var_dump($reserved_product);
            echo '</pre>';
            break;
        case 'massdata_reserver':

            $user_nicename = get_userdata($post->post_author)->data;
            echo $post->post_author.': ' . $user_nicename->user_nicename;
            break;
        case 'reservation_date':

            echo  $post->post_date_gmt;
            break;
        default:
            break;
     endswitch;
}

function quotation_remove_row_actions($actions, $post)
{

    global $current_screen;
    if ($current_screen->post_type != 'massdata_quotation') return actions;

    unset($actions['edit']);
    unset($actions['inline hide-if-no-js']);

    return $actions;

}
function quotation_remove_bulk_actions($actions)
{
    unset($actions['edit']);
    global $post;
    return $actions;
}

add_filter('manage_users_columns', 'massdata_manager_users_column', 90, 1);
add_action('manage_users_custom_column', 'massdata_manage_users_custom_columns', 90, 3);
function massdata_manager_users_column($columns){

    $columns['username'] = 'Fullname/Username';
    unset($columns['name']);
    unset($columns['posts']);
    unset($columns['woocommerce_billing_address']);
    unset($columns['woocommerce_shipping_address']);
    unset($columns['woocommerce_paying_customer']);
    unset($columns['woocommerce_order_count']);
    $columns['quote_count'] = 'Quotes';
    $columns['reserve_count'] = 'Reservation';

    //dumper($columns);
    return $columns;
}
function massdata_manage_users_custom_columns($value, $column_name, $user_id){

    switch($column_name){
        case 'quote_count':
            return $user_meta_obj = get_user_meta($user_id, 'quote_count', true);
            break;
        case 'reserve_count':
            return '';
            break;
        default:
            return $value;
            break;
    }
}

add_action('delete_user', 'massdata_before_user_delete', 10, 1);
add_action('before_delete_post', 'massdata_reduce_user_quote_count');
add_action('before_delete_post', 'massdata_before_quotation_delete');
function massdata_before_user_delete($user_id){

    $quote_count = get_user_meta($user_id, 'quote_count', true);
    if(isset($quote_count) && $quote_count > 0){
        echo "<script>alert('This user has quotation left in the system. Please delete all quotation from this user before removing the user\\'s account');</script>";

        //add_query('', '', $_SERVER['HTTP_REFERER']);
        exit(wp_redirect(admin_url().'users.php'));
    }
}
function massdata_reduce_user_quote_count($post_id){

}
function massdata_before_quotation_delete($post_id){

    if(get_the_ID() == $post_id){
        massdata_file_unlink($post_id);
    }
}

add_action('user_contactmethods', 'massdata_custom_user_fields', 10, 1);
function massdata_custom_user_fields($profilefields){

    //personal options
    //contact info
    //acount user
    //customer billing address
    //customer shipping address
    //add user edit page, and 'Your Profile' menu is the same(almost)
    // Difference between user account and profile is big, on is the data or state of user in system. Another is the display of the user in the systems.
    return $profilefields;
}

add_action('post_edit_form_tag', 'quotation_post_edit_form_tag');
function quotation_post_edit_form_tag()
{
    global $post;
    if (!$post) return;

    $post_type = get_post_type($post->ID);
    if ('product' != $post_type) return;
    echo 'enctype="multipart/form-data" encoding="multipart/form-data"';
}

add_filter( 'upload_dir', 'massdata_quotation_upload_directory' );
function massdata_file_unlink($post_id)
{
    $artwork_arr = get_post_meta($post_id, 'md_in_artwork');

// get the file name exclude from path, if explode '\' doesnt work use '/'
    $artwork_exploded = explode('/', $artwork_arr[0]['file']);
    if (!isset($artwork_exploded)) {
        $artwork_exploded = explode('\\', $artwork_arr[0]['file']);
    }
    end($artwork_exploded);
    $artwork_exploded = $artwork_exploded[key($artwork_exploded)];

// normalize the path to POSIX compliant, always forward slash
    $cwd_exploded = explode("\\", ABSPATH);
    if (isset($cwd_exploded)) {
        $cwd_exploded = implode("/", $cwd_exploded);
    }
    $cwd_artwork = $cwd_exploded . "wp-content/quote_design_folder";

// check if file is readable
    unlink($cwd_artwork.'/'.$artwork_exploded);
}
function massdata_quotation_upload_directory( $args ) {

    /**
     * Change Upload Directory for Custom Post-Type
     *
     * This will change the upload directory for a custom post-type. Attachments will
     * now be uploaded to an "uploads" directory within the folder of your plugin. Make
     * sure you swap out "post-type" in the if-statement with the appropriate value...
     */
    global $post;
    // Check the post-type of the current post
    if( "product" == get_post_type( $post->ID )) {
        if((isset($_POST['__md__']) && isset($_POST['md-in-product']))){

            $cwd_arr = getcwd();
            $cwd_exploded = explode("\\", $cwd_arr);
            if(isset($cwd_exploded)){
                $cwd_arr = implode("/", $cwd_exploded);
            }

            if(is_dir($cwd_arr.'/wp-content')){

                $args['path'] = $cwd_arr.'/wp-content'."/quote_design_folder";
                $args['url']  = content_url()."/quote_design_folder";
                $args['basedir'] = $cwd_arr.'/wp-content' . "/quote_design_folder";
                $args['baseurl'] = $cwd_arr.'/wp-content' . "/quote_design_folder";

                error_log("path={$args['path']}");
                error_log("url={$args['url']}");
                error_log("subdir={$args['subdir']}");
                error_log("basedir={$args['basedir']}");
                error_log("baseurl={$args['baseurl']}");
                error_log("error={$args['error']}");
            }
        }
    }
    return $args;
}

//contact us
add_shortcode('massdata_contact_us', 'massdata_contactus_shortcode');
function massdata_contactus_shortcode($atts){
    $atts = shortcode_atts(
        array(
        ), $atts
    );
    extract($atts);
    require_once get_template_directory().'/md_template_contact_us.php';
    return;
}

cron_delete_massdata_reservation();
function cron_delete_massdata_reservation(){

    $post_date = get_post_meta('post_date');
    $post_date_gmt = get_post_meta('post_date_gmt');


    // time(), strtotime
    // mm/dd/yyyy 00:00:00.

    // hard code three days

//    if (is_array($expirationtime)) {
//        $expirestring = implode($expirationtime);
//
//
//    }
    wp_trash_post(569);
}

//function retrieve_password() {
//
//    global $wpdb, $current_site, $wp_hasher;
//
//    $errors = new WP_Error();
//
//    if ( empty( $_POST['user_login'] ) ) {
//        $errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or e-mail address.'));
//    } else if ( strpos( $_POST['user_login'], '@' ) ) {
//        $user_data = get_user_by( 'email', trim( $_POST['user_login'] ) );
//        if ( empty( $user_data ) )
//            $errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.'));
//    } else {
//        $login = trim($_POST['user_login']);
//        $user_data = get_user_by('login', $login);
//    }
//
//    /**
//     * Fires before errors are returned from a password reset request.
//     *
//     * @since 2.1.0
//     */
//    do_action( 'lostpassword_post' );
//
//    if ( $errors->get_error_code() )
//        return $errors;
//
//    if ( !$user_data ) {
//        $errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or e-mail.'));
//        return $errors;
//    }
//
//    // redefining user_login ensures we return the right case in the email
//    $user_login = $user_data->user_login;
//    $user_email = $user_data->user_email;
//
//    /**
//     * Fires before a new password is retrieved.
//     *
//     * @since 1.5.2
//     * @deprecated 1.5.2 Misspelled. Use 'retrieve_password' hook instead.
//     *
//     * @param string $user_login The user login name.
//     */
//    do_action( 'retreive_password', $user_login );
//    /**
//     * Fires before a new password is retrieved.
//     *
//     * @since 1.5.2
//     *
//     * @param string $user_login The user login name.
//     */
//    do_action( 'retrieve_password', $user_login );
//
//    /**
//     * Filter whether to allow a password to be reset.
//     *
//     * @since 2.7.0
//     *
//     * @param bool true           Whether to allow the password to be reset. Default true.
//     * @param int  $user_data->ID The ID of the user attempting to reset a password.
//     */
//    $allow = apply_filters( 'allow_password_reset', true, $user_data->ID );
//
//    if ( ! $allow )
//        return new WP_Error('no_password_reset', __('Password reset is not allowed for this user'));
//    else if ( is_wp_error($allow) )
//        return $allow;
//
//    // Generate something random for a password reset key.
//    $key = wp_generate_password( 20, false );
//
//    /**
//     * Fires when a password reset key is generated.
//     *
//     * @since 2.5.0
//     *
//     * @param string $user_login The username for the user.
//     * @param string $key        The generated password reset key.
//     */
//    do_action( 'retrieve_password_key', $user_login, $key );
//
//    // Now insert the key, hashed, into the DB.
//    if ( empty( $wp_hasher ) ) {
//        require_once ABSPATH . 'wp-includes/class-phpass.php';
//        $wp_hasher = new PasswordHash( 8, true );
//    }
//    $hashed = $wp_hasher->HashPassword( $key );
//    $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ) );
//
//    $message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
//    $message .= network_home_url( '/' ) . "\r\n\r\n";
//    $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
//    $message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
//    $message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
//    $message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . ">\r\n";
//
//    if ( is_multisite() )
//        $blogname = $GLOBALS['current_site']->site_name;
//    else
//        // The blogname option is escaped with esc_html on the way into the database in sanitize_option
//        // we want to reverse this for the plain text arena of emails.
//        $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
//
//    $title = sprintf( __('[%s] Password Reset'), $blogname );
//
//    /**
//     * Filter the subject of the password reset email.
//     *
//     * @since 2.8.0
//     *
//     * @param string $title Default email title.
//     */
//    $title = apply_filters( 'retrieve_password_title', $title );
//    /**
//     * Filter the message body of the password reset mail.
//     *
//     * @since 2.8.0
//     *
//     * @param string $message Default mail message.
//     * @param string $key     The activation key.
//     */
//    $message = apply_filters( 'retrieve_password_message', $message, $key );
//
//    if ( $message && !wp_mail($user_email, $title, $message) )
//        wp_die( __('The e-mail could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function.') );
//
//    return true;
//}