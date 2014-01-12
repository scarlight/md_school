<?php
/**
 * Custom searchform
 *
 * Used to display if there is a 404 error on the webpage
 * WordPress site.
 *
 */
?>
<form method="get" id="searchform" action="<?php bloginfo('url') ?>">
    <div class="input-group">
        <div class="input-group-btn">
            <button class="btn btn-default" type="submit">
                <i class="icon-search icon-flip-horizontal"></i>
            </button>
        </div>
        <input type="text" class="form-control" placeholder="Search" name="s" id="s">
        
        <input type="hidden" name="post_type" value="product" />

        <?php
        //if(is_woocommerce()){
            //echo '<input type="hidden" name="post_type" value="product" />';
        //}
        ?>
    </div>
</form>
