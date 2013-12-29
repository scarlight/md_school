<div id="theme-sidebar">
    <ul>
        <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar()) : ?>
            <li><!-- stuff shown here in case no widgets active --></li>
        <?php endif; ?>
    </ul>
</div>