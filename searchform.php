<?php
/**
 * Custom searchform
 *
 * Used to display if there is a 404 error on the webpage
 * WordPress site.
 *
 */
?>

<form role="search" action="#" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
    <div class="input-group">
        <div class="input-group-btn">
            <button class="btn btn-default" type="submit">
                <i class="icon-search icon-flip-horizontal"></i>
            </button>
        </div>
        <input type="text" class="form-control" placeholder="Search" name="s" id="s">
    </div>
</form>
