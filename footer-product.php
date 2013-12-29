<?php

$massdata_footer_info = get_option("massdata_theme_options");

?>

                </div>
                <div id="powerista-footer-wrp">
                    <div id="powerista-footer">
                        <p class="copyright floatl"><?php echo $massdata_footer_info['massdata_copyright_tag'] ?></p>
                        <p class="terms">
                            <a class="privacy-policy" href="<?php echo $massdata_footer_info['massdata_privacy_policy'] ?>">Privacy Policy</a>
                            <a class="terms" href="<?php echo $massdata_footer_info['massdata_term_condition'] ?>">Terms of Use</a>
                            <a class="facebook" href="<?php echo $massdata_footer_info['massdata_facebook_page'] ?>">
                                <img src="<?php echo MASSDATA_THEMEROOT; ?>/images/facebook.png" width="27" height="27" alt="Facebook">Facebook
                            </a>
                        </p>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php wp_footer(); ?>
    </body>
</html>