<?php 

add_action('admin_menu', 'massdata_gcf_interface');

function massdata_gcf_interface() {
    add_options_page('MassData Settings', 'MassData Settings', '8', 'functions', 'editglobalcustomfields');
}

?>

<?php function editglobalcustomfields() {  ?>
<div class="wrap">
    <h2>MassData Settings</h2>
    <form method="post" action="options.php">
        <?php wp_nonce_field('update-options') ?>
        <p>
            <strong>MassData ID:</strong><br />
            <input type="text" name="massdataid" size="45" value="<?php echo get_option('massdataid'); ?>" />
        </p>
        <p>
            <input type="submit" name="Submit" value="Update Options" />
        </p>
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="massdataid" />
    </form>
</div>
<?php } ?>