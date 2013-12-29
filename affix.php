<?php 

$affix_links = get_option('massdata_theme_options');

?>
<div id="sidebar">
    <ul>
        <li><a class="sidebar-quote" href="<?php echo $affix_links['affix_quote'] ?>">Quote</a></li>
        <li><a class="sidebar-p" href="<?php echo $affix_links['affix_popular_pendrive'] ?>">Popular Pen Drives</a></li>
        <li><a class="sidebar-c" href="<?php echo $affix_links['affix_current_stock'] ?>">Current Stock</a></li>
        <li><a class="sidebar-d" href="<?php echo $affix_links['affix_designer_pendrive'] ?>">Designer Pen Drive</a></li>
        <li><a class="sidebar-u" href="<?php echo $affix_links['affix_usb_gadget'] ?>">USB Gadget</a></li>
        <li><a class="sidebar-e" href="<?php echo $affix_links['affix_electronic_gadget'] ?>">Electronic Gadget</a></li>
    </ul>
</div>