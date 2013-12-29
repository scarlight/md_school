<?php

// adapted from http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/

add_action( 'admin_init', 'theme_options_init' );
add_action( 'admin_menu', 'theme_options_add_page' );

/**
 * Init plugin options to white list our options
 */
function theme_options_init(){
	register_setting( 'massdata_option_settings', 'massdata_theme_options', 'theme_options_validate' );
}

/**
 * Load up the menu page
 */
function theme_options_add_page() {
	add_theme_page( 'MassData Theme Options', 'MassData Theme Options', 'edit_theme_options', 'massdata_theme_options', 'theme_options_do_page' );
}

/**
 * Create arrays for our select and radio options
 */
$radio_options = array(
    'yes' => array(
        'value' => 'yes',
        'label' => 'Yes'
    ),
    'no' => array(
        'value' => 'no',
        'label' => 'No'
    ),
    'maybe' => array(
        'value' => 'maybe',
        'label' => 'Maybe'
    )
);

function build_select_list(){
//    $page_args = array(
//        'sort_order' => 'ASC',
//        'sort_column' => 'post_title',
//        'hierarchical' => 1,
//        'exclude' => '',
//        'include' => '',
//        'meta_key' => '',
//        'meta_value' => '',
//        'authors' => '',
//        'child_of' => 0,
//        'parent' => -1,
//        'exclude_tree' => '',
//        'number' => '',
//        'offset' => 0,
//        'post_type' => 'page',
//        'post_status' => 'publish'
//    );
//    $list_of_pages = get_pages($page_args);
//
//    //"\n\t<option style=\"padding-right: 10px;\" value='" . esc_attr( $option['value'] ) . "'>$label</option>";
//    $select = "<select name>";
//    foreach ($list_of_pages as $list){
//
//    }


//    <select id="capacity" name="capacity" class="form-control selectpicker" required>
//        <option value="">- Select -</option>
//        <option value="2">2GB</option>
//        <option value="4">4GB</option>
//        <option value="8">8GB</option>
//        <option value="16">16GB</option>
//        <option value="2000">2000+MAH</option>
//        <option value="4000">4000+MAH</option>
//        <option value="5000">5000+MAH</option>
//        <option value="10000">10000+MAH</option>
//    </select>


//    echo "<pre>";
//    print_r($list_of_pages);
//    echo "</pre>";
}

/**
 * Create the options page
 */
function theme_options_do_page() {
	global $select_options, $radio_options;

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;

	?>

	<div class="wrap">

		<?php screen_icon(); echo "<h2>" . get_current_theme() . ' Theme Options' . "</h2>"; ?>

		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
            <div class="updated fade"><p><strong><?php echo 'Options saved'; ?></strong></p></div>
        <?php endif; ?>

        <form method="post" action="options.php">
			<?php settings_fields( 'massdata_option_settings' ); ?>
			<?php $options = get_option( 'massdata_theme_options' ); ?>

            <h2>Sidebar</h2>
            <table class="widefat fixed">
                <thead>
                <tr>
                    <th class="manage-column" style="width:230px;">Theme Location</th>
                    <th class="manage-column">Assign URL</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong><label for="massdata_theme_options[affix_quote]"><?php echo "Quote"; ?></label></strong></td>
                        <td>
                            <div>
                                <input style="width:100%;" id="massdata_theme_options[affix_quote]" class="regular-text" type="text" name="massdata_theme_options[affix_quote]" value="<?php esc_attr_e( $options['affix_quote'] ); ?>" placeholder="http://"/>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><label for="massdata_theme_options[affix_popular_pendrive]"><?php echo "Popular Pen Drives"; ?></label></strong></td>
                        <td>
                            <div>
                                <input style="width:100%;" id="massdata_theme_options[affix_popular_pendrive]" class="regular-text" type="text" name="massdata_theme_options[affix_popular_pendrive]" value="<?php esc_attr_e( $options['affix_popular_pendrive'] ); ?>" placeholder="http://"/>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><label for="massdata_theme_options[affix_designer_pendrive]"><?php echo "Designer Pendrive"; ?></label></strong></td>
                        <td>
                            <div>
                                <input style="width:100%;" id="massdata_theme_options[affix_designer_pendrive]" class="regular-text" type="text" name="massdata_theme_options[affix_designer_pendrive]" value="<?php esc_attr_e( $options['affix_designer_pendrive'] ); ?>" placeholder="http://"/>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><label for="massdata_theme_options[affix_current_stock]"><?php echo "Current Stock"; ?></label></strong></td>
                        <td>
                            <div>
                                <input style="width:100%;" id="massdata_theme_options[affix_current_stock]" class="regular-text" type="text" name="massdata_theme_options[affix_current_stock]" value="<?php esc_attr_e( $options['affix_current_stock'] ); ?>" placeholder="http://"/>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><label for="massdata_theme_options[affix_usb_gadget]"><?php echo "USB Gadget"; ?></label></strong></td>
                        <td>
                            <div>
                                <input style="width:100%;" id="massdata_theme_options[affix_usb_gadget]" class="regular-text" type="text" name="massdata_theme_options[affix_usb_gadget]" value="<?php esc_attr_e( $options['affix_usb_gadget'] ); ?>" placeholder="http://"/>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><label for="massdata_theme_options[affix_electronic_gadget]"><?php echo "Electronic Gadget"; ?></label></strong></td>
                        <td>
                            <div>
                                <input style="width:100%;" id="massdata_theme_options[affix_electronic_gadget]" class="regular-text" type="text" name="massdata_theme_options[affix_electronic_gadget]" value="<?php esc_attr_e( $options['affix_electronic_gadget'] ); ?>" placeholder="http://"/>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- /************************************************************************************************/ -->
            
            <br>
            <h2>PDF Download - Home Page</h2>
            <table class="widefat fixed">
                <thead>
                <tr>
                    <th class="manage-column" style="width:230px;">Theme Location</th>
                    <th class="manage-column">Assign URL</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong><label for="massdata_theme_options[massdata_popular_download_pdf]"><?php echo "Popular Pen Drive"; ?></label></strong></td>
                        <td>
                            <div>
                                <input style="width:100%;" id="massdata_theme_options[massdata_popular_download_pdf]" class="regular-text" type="text" name="massdata_theme_options[massdata_popular_download_pdf]" value="<?php esc_attr_e( $options['massdata_popular_download_pdf'] ); ?>" placeholder="http://"/>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><label for="massdata_theme_options[massdata_designer_download_pdf]"><?php echo "Designer Pen Drive"; ?></label></strong></td>
                        <td>
                            <div>
                                <input style="width:100%;" id="massdata_theme_options[massdata_designer_download_pdf]" class="regular-text" type="text" name="massdata_theme_options[massdata_designer_download_pdf]" value="<?php esc_attr_e( $options['massdata_designer_download_pdf'] ); ?>" placeholder="http://"/>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><label for="massdata_theme_options[massdata_usb_download_pdf]"><?php echo "USB Gadget"; ?></label></strong></td>
                        <td>
                            <div>
                                <input style="width:100%;" id="massdata_theme_options[massdata_usb_download_pdf]" class="regular-text" type="text" name="massdata_theme_options[massdata_usb_download_pdf]" value="<?php esc_attr_e( $options['massdata_usb_download_pdf'] ); ?>" placeholder="http://"/>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><label for="massdata_theme_options[massdata_electronic_download_pdf]"><?php echo "Electronic Gadget"; ?></label></strong></td>
                        <td>
                            <div>
                                <input style="width:100%;" id="massdata_theme_options[massdata_electronic_download_pdf]" class="regular-text" type="text" name="massdata_theme_options[massdata_electronic_download_pdf]" value="<?php esc_attr_e( $options['massdata_electronic_download_pdf'] ); ?>" placeholder="http://"/>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

			<!-- /************************************************************************************************/ -->

            <br>
            <h2>Other Information</h2>
            <table class="widefat fixed">
                <thead>
                <tr>
                    <th class="manage-column" style="width:230px;">Theme Location</th>
                    <th class="manage-column">Assign Information/URL</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong><label for="massdata_theme_options[massdata_copyright_tag]"><?php echo "Copyright Info"; ?></label></strong></td>
                        <td>
                            <div>
                                <input style="width:100%;" id="massdata_theme_options[massdata_copyright_tag]" class="regular-text" type="text" name="massdata_theme_options[massdata_copyright_tag]" value="<?php esc_attr_e( $options['massdata_copyright_tag'] ); ?>" placeholder="Copyright 2013 by Powerista Industries Sdn Bhd. All Rights Reserved."/>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><label for="massdata_theme_options[massdata_privacy_policy]"><?php echo "Privacy Policy"; ?></label></strong></td>
                        <td>
                            <div>
                                <input style="width:100%;" id="massdata_theme_options[massdata_privacy_policy]" class="regular-text" type="text" name="massdata_theme_options[massdata_privacy_policy]" value="<?php esc_attr_e( $options['massdata_privacy_policy'] ); ?>" placeholder="http://"/>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><label for="massdata_theme_options[massdata_term_condition]"><?php echo "Terms of Use"; ?></label></strong></td>
                        <td>
                            <div>
                                <input style="width:100%;" id="massdata_theme_options[massdata_term_condition]" class="regular-text" type="text" name="massdata_theme_options[massdata_term_condition]" value="<?php esc_attr_e( $options['massdata_term_condition'] ); ?>" placeholder="http://"/>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><label for="massdata_theme_options[massdata_facebook_page]"><?php echo "Facebook Page"; ?></label></strong></td>
                        <td>
                            <div>
                                <input style="width:100%;" id="massdata_theme_options[massdata_facebook_page]" class="regular-text" type="text" name="massdata_theme_options[massdata_facebook_page]" value="<?php esc_attr_e( $options['massdata_facebook_page'] ); ?>" placeholder="http://"/>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php echo 'Save Options'; ?>" />
			</p>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function theme_options_validate( $input ) {

	/**
	* For Affix
	*/

	// Text option must be safe text with no HTML tags
	$input['affix_quote'] = wp_filter_nohtml_kses( $input['affix_quote'] );
	if($input['affix_quote'] == "" || $input['affix_quote'] == " "){
		$input['affix_quote'] = "#";
	}

	// Text option must be safe text with no HTML tags
	$input['affix_popular_pendrive'] = wp_filter_nohtml_kses( $input['affix_popular_pendrive'] );
	if($input['affix_popular_pendrive'] == "" || $input['affix_popular_pendrive'] == " "){
		$input['affix_popular_pendrive'] = "#";
	}

	// Text option must be safe text with no HTML tags
	$input['affix_current_stock'] = wp_filter_nohtml_kses( $input['affix_current_stock'] );
	if($input['affix_current_stock'] == "" || $input['affix_current_stock'] == " "){
		$input['affix_current_stock'] = "#";
	}

	// Text option must be safe text with no HTML tags
	$input['affix_designer_pendrive'] = wp_filter_nohtml_kses( $input['affix_designer_pendrive'] );
	if($input['affix_designer_pendrive'] == "" || $input['affix_designer_pendrive'] == " "){
		$input['affix_designer_pendrive'] = "#";
	}

	// Text option must be safe text with no HTML tags
	$input['affix_usb_gadget'] = wp_filter_nohtml_kses( $input['affix_usb_gadget'] );
	if($input['affix_usb_gadget'] == "" || $input['affix_usb_gadget'] == " "){
		$input['affix_usb_gadget'] = "#";
	}

	// Text option must be safe text with no HTML tags
	$input['affix_electronic_gadget'] = wp_filter_nohtml_kses( $input['affix_electronic_gadget'] );
	if($input['affix_electronic_gadget'] == "" || $input['affix_electronic_gadget'] == " "){
		$input['affix_electronic_gadget'] = "#";
	}


	/**
	* For MassData Homepage PDF Download Link
	*/

    // Text option must be safe text with no HTML tags
    $input['massdata_popular_download_pdf'] = wp_filter_nohtml_kses( $input['massdata_popular_download_pdf'] );
    if($input['massdata_popular_download_pdf'] == "" || $input['massdata_popular_download_pdf'] == " "){
        $input['massdata_popular_download_pdf'] = "#";
    }

    // Text option must be safe text with no HTML tags
    $input['massdata_designer_download_pdf'] = wp_filter_nohtml_kses( $input['massdata_designer_download_pdf'] );
    if($input['massdata_designer_download_pdf'] == "" || $input['massdata_designer_download_pdf'] == " "){
        $input['massdata_designer_download_pdf'] = "#";
    }

	// Text option must be safe text with no HTML tags
	$input['massdata_usb_download_pdf'] = wp_filter_nohtml_kses( $input['massdata_usb_download_pdf'] );
	if($input['massdata_usb_download_pdf'] == "" || $input['massdata_usb_download_pdf'] == " "){
		$input['massdata_usb_download_pdf'] = "#";
	}

	// Text option must be safe text with no HTML tags
	$input['massdata_electronic_download_pdf'] = wp_filter_nohtml_kses( $input['massdata_electronic_download_pdf'] );
	if($input['massdata_electronic_download_pdf'] == "" || $input['massdata_electronic_download_pdf'] == " "){
		$input['massdata_electronic_download_pdf'] = "#";
	}


	/**
	* For MassData Other Information
	*/

	// Text option must be safe text with no HTML tags
	$input['massdata_copyright_tag'] = wp_filter_nohtml_kses( $input['massdata_copyright_tag'] );
	if($input['massdata_copyright_tag'] == "" || $input['massdata_copyright_tag'] == " "){
		$input['massdata_copyright_tag'] = "Copyright 2013 by Powerista Industries Sdn Bhd. All Rights Reserved.";
	}

	// Text option must be safe text with no HTML tags
	$input['massdata_privacy_policy'] = wp_filter_nohtml_kses( $input['massdata_privacy_policy'] );
	if($input['massdata_privacy_policy'] == "" || $input['massdata_privacy_policy'] == " "){
		$input['massdata_privacy_policy'] = "#";
	}

	// Text option must be safe text with no HTML tags
	$input['massdata_term_condition'] = wp_filter_nohtml_kses( $input['massdata_term_condition'] );
	if($input['massdata_term_condition'] == "" || $input['massdata_term_condition'] == " "){
		$input['massdata_term_condition'] = "#";
	}

	// Text option must be safe text with no HTML tags
	$input['massdata_facebook_page'] = wp_filter_nohtml_kses( $input['massdata_facebook_page'] );
	if($input['massdata_facebook_page'] == "" || $input['massdata_facebook_page'] == " "){
		$input['massdata_facebook_page'] = "#";
	}

	return $input;
}