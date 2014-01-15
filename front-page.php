<?php
/**
 * Custom front-page.php template file
 *
 * Used to display the homepage of your
 * WordPress site.
 *
 */
?>

<?php get_header(); ?>

<?php
if (have_posts()) :
   while (have_posts()) :
      the_post();
      the_content();
   endwhile;
endif;
?>

<?php get_template_part( 'massdata-carousel' ); ?>

<?php $front_page_links = get_option('massdata_theme_options'); ?>

<div class="clear"></div>
<div id="product-type">
    <div class="category type1">
         <h3>POPULAR PEN DRIVE</h3>
         <a href="<?php echo $front_page_links['affix_popular_pendrive'] ?>">
            <img src="<?php echo $front_page_links["image_popular_pendrive"] ?>" width="265" height="154" alt="POPULAR PEN DRIVE">
         </a>
         <a class="download popular-pendrive" href="<?php echo $front_page_links['massdata_popular_download_pdf'] ?>"><img src="<?php echo MASSDATA_THEMEROOT; ?>/images/download.png" width="35" height="42" alt=""></a>
    </div>
    <div class="category type2">
         <h3>CURRENT STOCK</h3>
         <a href="<?php echo $front_page_links['affix_current_stock'] ?>">
            <img src="<?php echo $front_page_links["image_designer_pendrive"] ?>" width="265" height="154" alt="CURRENT STOCK">
         </a>
    </div>
    <div class="category type3">
         <h3>DESIGNER PEN DRIVE</h3>
         <a href="<?php echo $front_page_links['affix_designer_pendrive'] ?>">
            <img src="<?php echo $front_page_links["image_current_stock"] ?>" width="265" height="154" alt="DESIGNER PEN DRIVE">
         </a>
         <a class="download designer-pendrive" href="<?php echo $front_page_links['massdata_designer_download_pdf'] ?>"><img src="<?php echo MASSDATA_THEMEROOT; ?>/images/download.png" width="35" height="42" alt=""></a>
    </div>
    <div class="clear"></div>
    <div class="other-category">
        <div class="type4">
            <h5>
                <img src="<?php echo $front_page_links["image_usb_gadget"] ?>" width="111" height="77" alt="USB GADGET">
                <a href="<?php echo $front_page_links['affix_usb_gadget'] ?>">
                   USB GADGET
                </a>
                <a class="download" href="<?php echo $front_page_links['massdata_usb_download_pdf'] ?>"><img src="<?php echo MASSDATA_THEMEROOT; ?>/images/download.png" width="35" height="42" alt=""></a>
            </h5>
        </div>
        <div class="type5">
            <h5>
                <img src="<?php echo $front_page_links["image_electronic_gadget"] ?>" width="111" height="77" alt="ELECTRONIC GADGET">
                <a href="<?php echo $front_page_links['affix_electronic_gadget'] ?>">
                   ELECTRONIC GADGET
                </a>
                <a class="download" href="<?php echo $front_page_links['massdata_electronic_download_pdf'] ?>"><img src="<?php echo MASSDATA_THEMEROOT; ?>/images/download.png" width="35" height="42" alt=""></a>
            </h5>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>

<?php get_footer(); ?>