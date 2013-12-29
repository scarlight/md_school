<?php ob_start(); ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> >
<head>  
    <title>
        <?php 
            if (function_exists('is_tag') && is_tag())
            {
                single_tag_title('Tag Archive for &quot;'); echo '&quot; - ';
            }
            elseif (is_archive())
            {
                wp_title(''); echo ' Archive - ';
            }
            elseif (is_search())
            {
                echo 'Search for &quot;'.wp_specialchars($s).'&quot; - ';
            }
            elseif (!(is_404()) && (is_single()) || (is_page()))
            {
                if(is_front_page())
                {
                    bloginfo('description'); echo ' - ';
                }
                else
                {
                    wp_title(''); echo ' - ';
                }
            }
            elseif (is_404())
            {
                echo 'Not Found - ';
            }
            if (is_home())
            {
                bloginfo('name'); echo ' - '; bloginfo('description');
            }
            else
            {
                bloginfo('name');
            }
            if ($paged > 1)
            {
                echo ' - page '. $paged;
            }
        ?>
    </title>
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>"; charset="<?php bloginfo('charset'); ?>" />
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="url" content="<?php bloginfo('url'); ?>">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <meta name="author" content="<?php bloginfo('name'); ?>">
    <meta name="designer" content="Rich Codesign">  
    <link rel="stylesheet" href="<?php bloginfo("stylesheet_url"); ?>" type="text/css" media="screen" />
    <?php wp_enqueue_script('jquery'); ?>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <div id="browser-wrapper">
        <div id="main-wrapper">
            <div id="viewport-wrapper">
                <div id="powerista-header-wrp">
                    <div id="powerista-header">
                        <div id="powerista-company-logo">
                            <h1 id="company"><?php bloginfo('name'); ?><a title="<?php bloginfo('name'); ?>" href="<?php echo home_url(); ?>"><div id="company-logo"></div></a></h1>
                        </div>
                        <?php locate_template("md_login_form.php", true, true); ?>
                        <div class="clear"></div>
                        <?php 
                            $defaults = array(
                                'theme_location'  => 'main-menu',
                                'menu'            => '',
                                'container'       => '',
                                'container_class' => '',
                                'container_id'    => '',
                                'menu_class'      => 'sf-menu',
                                'menu_id'         => 'top-mainmenu',
                                'echo'            => true,
                                'fallback_cb'     => 'wp_page_menu',
                                'before'          => '',
                                'after'           => '',
                                'link_before'     => '',
                                'link_after'      => '',
                                'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                                'depth'           => 0,
                                'walker'          => ''
                            );
                            wp_nav_menu( $defaults );
                        ?>
                    </div>
                    <div id="title-bar-wrp">
                        <div>
                            <h1 class="title-bar">
                                <?php 
                                    if ( function_exists( 'breadcrumb_trail' ) ){
                                        breadcrumb_trail(
                                            array(
                                                'show_on_front'=> false,
                                                'separator' => '&gt;',
                                                'show_browse' => false
                                            )
                                        );
                                    }
                                ?>
                            </h1>
                        </div>
                    </div>
                </div>
                <div id="powerista-body-wrp">
                    <div id="powerista-body">
                        <?php locate_template("affix.php", true, true); ?>
