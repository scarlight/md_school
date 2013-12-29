<?php

// adapted from http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/


add_action( 'admin_menu', 'massdata_quotation_add_page' );

/**
 * Load up the menu page
 */
function massdata_quotation_add_page() {
	add_theme_page( 'MassData Quotation Info', 'MassData Quotation Info', 'edit_theme_options', 'massdata_quotation_info_options', 'massdata_quotation_info_do_page' );
}

/**
 * Create the options page
 */
function massdata_quotation_info_do_page() {
	?>

	<div class="wrap">

		<?php screen_icon(); echo "<h2>Quotation Information</h2>"; ?><br>

        <table class="widefat fixed">
            <thead>
            <tr>
                <th class="manage-column" style="width:200px;">Subject</th>
                <th class="manage-column" style="width:5px;"></th>
                <th class="manage-column">Description</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Quotation by</strong></td>
                    <td><strong>:</strong></td>
                    <td>
                        <div>
                            answered
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Submitted date</strong></td>
                    <td><strong>:</strong></td>
                    <td>
                        <div>
                            answered
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Product</strong></td>
                    <td><strong>:</strong></td>
                    <td>
                        <div>
                            answered
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Quantity</strong></td>
                    <td><strong>:</strong></td>
                    <td>
                        <div>
                            answered
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Capacity</strong></td>
                    <td><strong>:</strong></td>
                    <td>
                        <div>
                            answered
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Product Color</strong></td>
                    <td><strong>:</strong></td>
                    <td>
                        <div>
                            answered
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Logo option (Front)</strong></td>
                    <td><strong>:</strong></td>
                    <td>
                        <div>
                            answered
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>No. of Color (Front Logo)</strong></td>
                    <td><strong>:</strong></td>
                    <td>
                        <div>
                            answered
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Logo option (Back)</strong></td>
                    <td><strong>:</strong></td>
                    <td>
                        <div>
                            answered
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>No. of Color (Back Logo)</strong></td>
                    <td><strong>:</strong></td>
                    <td>
                        <div>
                            answered
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Design/ Artwork</strong></td>
                    <td><strong>:</strong></td>
                    <td>
                        <div>
                            answered
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Accessories</strong></td>
                    <td><strong>:</strong></td>
                    <td>
                        <div>
                            answered
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Packaging</strong></td>
                    <td><strong>:</strong></td>
                    <td>
                        <div>
                            answered
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Printing for Packaging</strong></td>
                    <td><strong>:</strong></td>
                    <td>
                        <div>
                            answered
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Budget (RM)</strong></td>
                    <td><strong>:</strong></td>
                    <td>
                        <div>
                            answered
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Deadline (DD MM YYYY)</strong></td>
                    <td><strong>:</strong></td>
                    <td>
                        <div>
                            answered
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Other requirement</strong></td>
                    <td><strong>:</strong></td>
                    <td>
                        <div>
                            answered
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
            
	</div>
	<?php
}
