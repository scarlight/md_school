<?php
require_once 'md_validator.php';
require_once 'md_post_data.php';

global $post;
$massdata_quotation_message = null;
$quote_request = false;
// is send from quote when user is logged in???
if (isset($_POST['md-send']) && $_POST['md-send'] === 'Send') {

        $quote_request = true;
        $errors = general_quote_validator();

        foreach ($errors as $error => $fields) {
            foreach ($fields as $field => $message) {
                if (!empty($message)) {
                    $massdata_quotation_message = $message;
                }
            }
            if (!is_null($massdata_quotation_message)) {
                break;
            }
        }
        if (is_null($massdata_quotation_message)) {

            $result = send_general_quote();
            if (empty($result) && $quote_request == true) {
                $massdata_quotation_message = "Quotation is successfully";
            }else{
                $massdata_quotation_message = $result[0];
            }
        }

} else if (isset($_GET['status']) && $_GET['status'] === 'submit') {

    if (isset($_GET['message'])) {
        $massdata_quotation_message = $_GET['message'];
    }
}

?>
<div id="browser-wrapper">
<div id="main-wrapper">
<div id="viewport-wrapper">
<div id="powerista-body-wrp">
<div id="powerista-body">
<div id="sidebar">
    <ul>
        <li><a class="sidebar-quote" href="#">Quote</a></li>
        <li><a class="sidebar-p" href="#">Popular Pen Drives</a></li>
        <li><a class="sidebar-c" href="#">Current Stock</a></li>
        <li><a class="sidebar-d" href="#">Designer Pen Drive</a></li>
        <li><a class="sidebar-u" href="#">USB Gadget</a></li>
        <li><a class="sidebar-e" href="#">Electronic Gadget</a></li>
    </ul>
</div>

<div id="powerista-body2">
<br>

<div id="orange-quote">
<?php if ($massdata_quotation_message != null) ;
echo "<p><strong>{$massdata_quotation_message}</strong></p>";
$massdata_quotation_message = null; ?>
<form action="<?php if (!is_user_logged_in()) echo wp_registration_url(); ?>"
      method='post' enctype="multipart/form-data" encoding="multipart/form-data">
<div class="left-form">
    <div class="form-group">
        <label for="product" class="control-label">Product <span class="required">*</span></label>
        <input type="text" class="form-control" id="product" name="md-in-product-name" placeholder="" required>
    </div>
    <div class="form-group">
        <label for="quantity" class="control-label">Quantity <span class="required">*</span></label>
        <input type="text" class="form-control" id="quantity" name="md-in-quantity" placeholder=""
               value="<?php if (isset($_POST['md-in-quantity'])) echo esc_attr($_POST['md-in-quantity']); ?>"
               required>
    </div>
    <div class="form-group">
        <label for="capacity" class="control-label">Capacity <span class="required">*</span></label>
        <select id="capacity" name="md-in-capacity" class="form-control selectpicker" required>
            <option value="select">- Select -</option>
            <option value="2">2GB</option>
            <option value="4">4GB</option>
            <option value="8">8GB</option>
            <option value="16">16GB</option>
            <option value="2000">2000+MAH</option>
            <option value="4000">4000+MAH</option>
            <option value="5000">5000+MAH</option>
            <option value="10000">10000+MAH</option>
        </select>
    </div>
    <div class="form-group">
        <label for="product-color" class="control-label">Product Color</label>
        <input type="text" class="form-control" id="product-color" name="md-in-product-color"
               value="<?php if (isset($_POST['md-in-product-color'])) echo esc_attr($_POST['md-in-product-color']); ?>">
    </div>
    <div class="form-group">
        <label for="logo-front" class="control-label">Logo option (front) <span class="required">*</span></label>
        <select id="logo-front" name="md-in-logo-front" class="form-control selectpicker" required>
            <option value="select">- Select -</option>
            <option value="none">None</option>
            <option value="printing">Printing</option>
            <option value="laser-engraving">Laser Engraving</option>
            <option value="emboss">Emboss</option>
        </select>

        <div class="classification">
            <label for="logo-front-color" class="control-label"
                   style="margin-top:5px; width:74px; font-style:italic;">No.
                of color</label>
            <input type="text" class="form-control floatr" id="logo-front-color" name="md-in-logo-front-color"
                   value="<?php if (isset($_POST['md-in-logo-front-color'])) echo esc_attr($_POST['md-in-logo-front-color']); ?>"
                   style="width:190px; margin-right:2px;">

            <div class="clear"></div>
        </div>
    </div>
    <div class="form-group">
        <label for="logo-back" class="control-label">Logo option (back) <span class="required">*</span></label>
        <select id="logo-back" name="md-in-logo-back" class="form-control selectpicker" required>
            <option value="select">- Select -</option>
            <option value="none">None</option>
            <option value="printing">Printing</option>
            <option value="laser-engraving">Laser Engraving</option>
            <option value="emboss">Emboss</option>
        </select>

        <div class="classification">
            <label for="logo-back-color" class="control-label"
                   style="margin-top:5px; width:74px; font-style:italic;">No.
                of color</label>
            <input type="text" class="form-control floatr" id="logo-back-color" name="md-in-logo-back-color"
                   value="<?php if (isset($_POST['md-in-logo-back-color'])) echo esc_attr($_POST['md-in-logo-back-color']); ?>"
                   style="width:190px; margin-right:2px;">

            <div class="clear"></div>
        </div>
    </div>
    <div class="form-group fileinput fileinput-new" data-provides="fileinput">
        <label for="artwork" class="control-label">Upload Design/Artwork</label>

        <div class="input-group">
            <div class="form-control uneditable-input" data-trigger="fileinput">
                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                <span class="fileinput-filename"></span>
            </div>
                <span class="input-group-addon btn btn-default btn-file">
                    <span class="fileinput-new"> Browse &nbsp;</span>
                    <input type="file" id="artwork" name="md-in-artwork">
                </span>
            <a href="#" class="input-group-addon btn btn-default fileinput-exists"
               data-dismiss="fileinput">Remove</a>
        </div>
    </div>
    <div class="form-group">
        <label for="accessories-type" class="control-label">Accessories <span class="required">*</span></label>
        <select id="accessories-type" name="md-in-accessories-type" class="form-control selectpicker">
            <option value="select">- Select -</option>
            <option value="none">None</option>
            <option value="lanyard">Lanyard</option>
            <option value="key-ring">Key Ring</option>
        </select>
    </div>
    <div class="form-group">
        <label for="packaging-type" class="control-label">Packaging <span class="required">*</span></label>
        <select id="packaging-type" name="md-in-packaging-type" class="form-control selectpicker">
            <option value="select">- Select -</option>
            <option value="none">None</option>
            <option value="plastic-box">Plastic Box</option>
            <option value="tin-box">Tin Box</option>
            <option value="mini-tin-box">Mini Tin Box</option>
            <option value="white-paper-box">White Paper Box</option>
            <option value="recycle-paper-box">Recycle Paper Box</option>
            <option value="mini-recycle-paper-box">Mini Recycle Paper Box</option>
        </select>
    </div>
    <div class="form-group">
        <label for="print-packaging" class="control-label">Printing for Packaging</label>
        <select id="print-packaging" name="md-in-print-packaging" class="form-control selectpicker">
            <option value="select">- Select -</option>
            <option value="none">None</option>
            <option value="printing">Printing</option>
            <option value="laser-engraving">Laser Engraving</option>
        </select>

        <div class="classification">
            <label for="print-packaging-color" class="control-label"
                   style="margin-top:5px; width:74px; font-style:italic;">No. of color</label>
            <input type="text" class="form-control floatr" id="print-packaging-color"
                   name="md-in-print-packaging-color"
                   value="<?php if (isset($_POST['md-in-packaging-color'])) echo esc_attr($_POST['md-in-packaging-color']) ?>"
                   style="width:190px; margin-right:2px;">

            <div class="clear"></div>
        </div>
    </div>
    <div class="form-group">
        <label for="budget" class="control-label">Budget <i>(if any)</i></label>
        <input type="text" class="form-control" id="budget" name="md-in-budget"
               value="<?php if (isset($_POST['md-in-budget'])) echo esc_attr($_POST['md-in-budget']) ?>">
    </div>
    <div class="form-group">
        <label for="deadline" class="control-label">Deadline <i>(if any)</i></label>
        <input type="text" class="form-control datepicker" id="deadline" name="md-in-deadline"
               value"<?php if (isset($_POST['md-in-deadline'])) echo esc_attr($_POST['md-in-deadline']); ?>">
    </div>
    <div class="form-group">
        <label for="other-requirement" class="control-label aligntop">Other Requirement</label>
        <textarea class="form-control textarea" name="md-in-other-requirement" id="other-requirement" cols="3"
                  rows="4"
                  placeholder="Please write here for further inquiry, information or important details. Example : Request a USB 3.0 pircing specify pantone color code in your logo, etc."
                  value="<?php if (isset($_POST['md-in-other-requirement'])) echo esc_attr($_POST['md-in-other-requirement']) ?>"></textarea>
    </div>
</div>

<!-- separator -->
<?php if (!is_user_logged_in()) { ?>
    <div class="right-form">
        <div class="form-group">
            <label for="name" class="control-label">Full Name <span class="required">*</span></label>
            <input type="text" class="form-control" id="name" name="user_login" placeholder="First and Last Name"
                   value="<?php if (isset($_POST['user_login'])) echo esc_attr($_POST['user_login']) ?>" required>
        </div>
        <div class="form-group">
            <label for="email" class="control-label">Email Address <span class="required">*</span></label>
            <input type="email" class="form-control" id="email" name="user_email" placeholder="abc@gmail.com"
                   value="<?php if (isset($_POST['user_email'])) echo esc_attr($_POST['user_email']) ?>" required>
        </div>
        <div class="form-group classification">
            <input type="radio" id="agent" name="md-in-group" value="agent">
            <label for="agent" class="control-label aligntop">Agent</label>
            <input type="radio" id="corporate" name="md-in-group" value="corporate">
            <label for="corporate" class="control-label aligntop">Corporate</label>
        </div>
        <div class="form-group">
            <label for="companyname" class="control-label">Company name <span class="required">*</span></label>
            <input type="text" class="form-control" id="companyname" name="md-in-companyname"
                   placeholder="Name of Your Company"
                   value="<?php if (isset($_POST['md-in-companyname'])) echo esc_attr($_POST['md-in-companyname']) ?>"
                   required>
        </div>
        <div class="form-group">
            <label for="registration-no" class="control-label">Co / Bis Reg. no.</label>
            <input type="text" class="form-control" id="registration-no" name="md-in-registration-no"
                   value="<?php if (isset($_POST['md-in-registration-no'])) echo esc_attr($_POST['md-in-registration-no']) ?>">
        </div>
        <div class="form-group">
            <label for="mobile" class="control-label">Mobile <span class="required">*</span></label>
            <input type="text" class="form-control" id="mobile" name="md-in-mobile" placeholder="e.g. 601X XXXXXXX"
                   value="<?php if (isset($_POST['md-in-mobile'])) echo esc_attr($_POST['md-in-mobile']) ?>"
                   required>
        </div>
        <div class="form-group">
            <label for="tel" class="control-label">Tel</label>
            <input type="text" class="form-control" id="tel" name="md-in-telephone" placeholder="e.g. 601X XXXXXXX"
                   value="<?php if (isset($_POST['md-in-telephone'])) echo esc_attr($_POST['md-in-telephone']) ?>">
        </div>
        <div class="form-group">
            <label for="fax" class="control-label">Fax</label>
            <input type="text" class="form-control" id="fax" name="md-in-fax" placeholder="e.g. 601X XXXXXXX"
                   value="<?php if (isset($_POST['md-in-fax'])) echo esc_attr($_POST['md-in-fax']) ?>">
        </div>
        <div class="form-group">
            <label for="address" class="control-label aligntop">Address <span class="required">*</span></label>
            <textarea class="form-control textarea" name="md-in-address" id="address" cols="30" rows="4"
                      value="<?php if (isset($_POST['md-in-address'])) echo esc_attr($_POST['md-in-address']) ?>"
                      required></textarea>
        </div>

        <div class="form-group">
            <label for="" class="control-label aligntop">Survey <span class="required">*</span></label>

            <input type="checkbox" id="exhibition" name="md-in-survey[]" value="exhibition">
            <label for="exhibition" class="control-label aligntop">Exhibition</label>

            <input type="checkbox" id="search-engine" name="md-in-survey[]" value="search-engine">
            <label for="search-engine" class="control-label aligntop">Search Engine</label>

            <div class="classification">
                <input type="checkbox" id="email-survey" name="md-in-survey[]" value="email-survey">
                <label for="email-survey" class="control-label aligntop">Email</label>

                <input type="checkbox" id="friendfamily" name="md-in-survey[]" value="friendfamily">
                <label for="friendfamily" class="control-label aligntop">Friends &amp; Families</label>
            </div>

            <div class="classification">
                <input type="checkbox" id="advertisement" name="md-in-survey[]" value="advertisement">
                <label for="advertisement" class="control-label aligntop">Advertisement</label>

                <input type="checkbox" id="facebook" name="md-in-survey[]" value="facebook">
                <label for="facebook" class="control-label aligntop">Facebook</label>
            </div>

            <div class="classification">
                <input type="checkbox" id="others" name="md-in-survey[]" value="others">
                <label for="others" class="control-label aligntop" style="width:50px;">Others</label>
                <input type="text" class="form-control floatr" id="others-desc" name="md-in-others-desc"
                       value="<?php if (isset($_POST['md-in-others-desc'])) echo esc_attr($_POST['md-in-others-desc']) ?>"
                       style="width:215px; margin-right:32px;">

                <div class="clear"></div>
            </div>

        </div>

        <div class="form-group">
            <label for="enquiry" class="control-label aligntop">Enquiry <span class="required">*</span></label>
            <textarea class="form-control textarea" name="md-in-enquiry" id="enquiry" cols="3"
                      value="<?php if (isset($_POST['md-in-enquiry'])) echo esc_attr($_POST['md-in-enquiry']) ?>"
                      rows="4" required></textarea>
        </div>

        <div class="form-group">
            <label for="" class="control-label aligntop">Register to access Current Stock <br>Pricing <span
                    class="required">*</span></label>

            <input type="radio" id="yes-pricing" name="md-in-view-pricing" value="yes">
            <label for="yes-pricing" class="control-label aligntop">Yes</label>
            <input type="radio" id="no-pricing" name="md-in-view-pricing" value="no">
            <label for="no-pricing" class="control-label aligntop">No</label>
        </div>
    </div>
<?php } ?>
<div class="clear"></div>

<div class="form-group">
    <p class="floatl" style="font-style:italic;">
        "<span class="required" style="font-style:normal;">*</span>" is the mandatory field
    </p>

    <div class="clear"></div>
    <hr class="dotted">
    <br>

    <div style="width:185px; margin: 0px auto;">

        <input type="reset" class="floatr btn btn-default" name='reset' value="Reset">
        <?php if (!is_user_logged_in()) do_action('register_form'); ?>
        <input type="submit" class="floatr btn btn-default" name="<?php if (is_user_logged_in()) {
            echo esc_attr('md-send');
        } else {
            echo esc_attr('md-submit');
        } ?>" value="<?php if (is_user_logged_in()) {
            echo esc_attr('Send');
        } else {
            echo esc_attr('Submit');
        } ?>">
    </div>
    <div class="clear"></div>
</div>

</form>
<div class="clear"></div>
</div>
</div>
</div>