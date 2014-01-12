<?php
/**
 * Custom searchform for 404
 *
 * Used to display a search form on a 404 error on the webpage
 * WordPress site.
 *
 */
?>

<div id="for-404">
    <form method="get" id="searchform-404" action="<?php bloginfo('url'); ?>">
        <div class="input-group">
            <div class="input-group-btn">
                <button class="btn btn-default" type="submit">
                    <i class="icon-search icon-flip-horizontal"></i>
                </button>
            </div>
            <input type="text" class="form-control" placeholder="Search" name="s" id="s-404">

            <input type="hidden" name="post_type" value="product" />
        </div>
    </form>
</div>
