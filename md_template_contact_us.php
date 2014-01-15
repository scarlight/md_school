<?php
$message = null;

if (!empty($_POST)) {

    if (isset($_POST['user-login'])) {
        $_POST['user-login'] = wp_strip_all_tags($_POST['user-login']);

    } else {
        $message = 'Please enter your name';
    }

    if (isset($_POST['user-email'])) {

        $_POST['user-email'] = wp_strip_all_tags($_POST['user-email']);
    } else {
        $message = 'Please enter your email';
    }

    if (isset($_POST['md-in-group'])) {
        $_POST['md-in-group'] = wp_strip_all_tags($_POST['md-in-group']);
    } else {
        $message = 'Please enter your role. Agent or Corporate?';
    }

    if (isset($_POST['md-in-companyname'])) {

        $_POST['md-in-companyname'] = wp_strip_all_tags($_POST['md-in-companyname']);
    } else {
        $message = 'Please enter your company name';
    }

    if (isset($_POST['md-in-registration-no'])) {
        $_POST['md-in-registration-no'] = wp_strip_all_tags($_POST['md-in-registration-no']);
    } else {

    }

    if (isset($_POST['md-in-mobile'])) {
        $_POST['md-in-mobile'] = wp_strip_all_tags($_POST['md-in-mobile']);
    } else {
        $message = 'Please enter your mobile number';
    }

    if (isset($_POST['md-in-telephone'])) {

        $_POST['md-in-telephone'] = wp_strip_all_tags($_POST['md-in-telephone']);
    } else {

    }

    if (isset($_POST['md-in-fax'])) {
        $_POST['md-in-fax'] = wp_strip_all_tags($_POST['md-in-fax']);
    } else {

    }

    if (isset($_POST['md-in-address'])) {
        $_POST['md-in-address'] = wp_strip_all_tags($_POST['md-in-address']);
    } else {
        $message = 'Please enter your address';
    }

    if (empty($_POST['md-in-survey'])) {
        $_POST['md-in-survey'] = wp_strip_all_tags($_POST['md-in-survey']);

        if(in_array('others', $_POST['md-in-survey'])){
            if(!isset($_POST['md-in-others-desc'])){
                $message = 'Please enter survey section';
            }
        }
    } else {
        $message = 'Please enter survey section';
    }

    if (isset($_POST['md-in-enquiry'])) {
        $_POST['md-in-enquiry'] = wp_strip_all_tags($_POST['md-in-enquiry']);
    } else {
        $message = 'Please enter your enquiry';
    }

    // throw data into a function and let is email the admin
    require_once get_template_directory().'/md_email_template_contactus.php';
    $message = send_admin_email_contact_us();
}
?>


<div class="row" id="contact-page">

    <div class="left floatl">
        <?php if (isset($message)) echo '<p>' . $message . '</p>'; ?>


        <?php
            global $wp;
            $current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
        ?>
        <form action="" method="post">
            <div class="form-group"><label class="control-label" for="name">Full Name <span
                        class="required">*</span></label> <input class="form-control" id="name" type="text" name="user-login"
                                                                 placeholder="First and Last Name" required=""/></div>
            <div class="form-group"><label class="control-label" for="email">Email Address <span
                        class="required">*</span></label> <input class="form-control" id="email" type="email"
                                                                 name="user-email" placeholder="abc@gmail.com" required=""/>
            </div>
            <div class="form-group classification"><input id="agent" type="radio" name="md-in-group" value="agent"/> <label
                    class="control-label aligntop" for="agent">Agent</label> <input id="corporate" type="radio"
                                                                                    name="md-in-group" value="corporate"/>
                <label class="control-label aligntop" for="corporate">Corporate</label></div>
            <div class="form-group"><label class="control-label" for="companyname">Company name <span
                        class="required">*</span></label> <input class="form-control" id="companyname" type="text"
                                                                 name="md-in-companyname" placeholder="Name of Your Company"
                                                                 required=""/></div>
            <div class="form-group"><label class="control-label" for="registration-no">Co / Bis Reg. no.</label> <input
                    class="form-control" id="registration-no" type="text" name="md-in-registration-no"/></div>
            <div class="form-group"><label class="control-label" for="mobile">Mobile <span
                        class="required">*</span></label> <input class="form-control" id="mobile" type="text"
                                                                 name="md-in-mobile" placeholder="e.g. 601X XXXXXXX"
                                                                 required=""/></div>
            <div class="form-group"><label class="control-label" for="tel">Tel</label> <input class="form-control"
                                                                                              id="tel" type="text"
                                                                                              name="md-in-telephone"
                                                                                              placeholder="e.g. 601X XXXXXXX"/>
            </div>
            <div class="form-group"><label class="control-label" for="fax">Fax</label> <input class="form-control"
                                                                                              id="fax" type="text"
                                                                                              name="md-in-fax"
                                                                                              placeholder="e.g. 601X XXXXXXX"/>
            </div>
            <div class="form-group"><label class="control-label aligntop" for="address">Address <span
                        class="required">*</span></label> <textarea class="form-control textarea" id="address" cols="30"
                                                                    name="md-in-address" required="" rows="4"></textarea>
            </div>
            <div class="form-group"><label class="control-label aligntop" for="">Survey <span class="required">*</span></label>
                <input id="exhibition" type="checkbox" name="survey" value="exhibition"/> <label
                    class="control-label aligntop" for="exhibition">Exhibition</label> <input id="search-engine"
                                                                                              type="checkbox"
                                                                                              name="md-in-survey[]"
                                                                                              value="search-engine"/>
                <label class="control-label aligntop" for="search-engine">Search Engine</label>

                <div class="classification"><input id="email-survey" type="checkbox" name="md-in-survey[]"
                                                   value="email-survey"/> <label class="control-label aligntop"
                                                                                 for="email-survey">Email</label> <input
                        id="friendfamily" type="checkbox" name="md-in-survey[]" value="friendfamily"/> <label
                        class="control-label aligntop" for="friendfamily">Friends &amp; Families</label></div>
                <div class="classification"><input id="advertisement" type="checkbox" name="md-in-survey[]"
                                                   value="advertisement"/> <label class="control-label aligntop"
                                                                                  for="advertisement">Advertisement</label>
                    <input id="facebook" type="checkbox" name="md-in-survey[]" value="facebook"/> <label
                        class="control-label aligntop" for="facebook">Facebook</label></div>
                <div class="classification"><input id="others" type="checkbox" name="md-in-survey[]" value="others"/> <label
                        class="control-label aligntop" style="width: 50px;" for="others">Others</label> <input
                        class="form-control floatr" id="others-desc" style="width: 220px; margin-right: 2px;"
                        type="text" name="md-in-others-desc"/>

                    <div class="clear"></div>
                </div>
            </div>
            <div class="form-group"><label class="control-label aligntop" for="enquiry">Enquiry <span
                        class="required">*</span></label> <textarea class="form-control textarea" id="enquiry" cols="3"
                                                                    name="md-in-enquiry" required="" rows="4"></textarea>
            </div>
            &nbsp;
            <div class="form-group">

                <hr class="stiched"/>
                <p class="floatl" style="margin-top: 30px; font-style: italic;">"<span class="required"
                                                                                       style="font-style: normal;">*</span>"
                    is the mandatory field</p>
                <br class="none"/> <input class="floatr btn btn-default" type="reset" value="Reset"/> <input
                    class="floatr btn btn-default" type="submit" value="Submit"/>

                <div class="clear"></div>
            </div>
        </form>
    </div>
    <div class="right floatr">
        <h6 class="black-heading">POWERISTA INDUSTRIES SDN BHD</h6>

        <hr class="dotted" style="width: 330px;"/>

        No. 17-1, Lorong 6A/91,
        Taman Shamelin Perkasa, Cheras,
        56100 Kuala Lumpur
        <table>
            <tbody>
            <tr>
                <td style="width: 60px;"><strong>Phone :</strong></td>
                <td>603 - 9283 8988</td>
            </tr>
            <tr>
                <td style="width: 60px;"><strong>Fax :</strong></td>
                <td>603 - 9201 0988</td>
            </tr>
            <tr>
                <td style="width: 60px;"><strong>Email :</strong></td>
                <td>desmond@powerista.com.my</td>
            </tr>
            </tbody>
        </table>
        &nbsp;
        <div>
            <iframe
                src="https://maps.google.com/maps/ms?msa=0&amp;msid=208986672469242443375.0004ecaba41bd6f35f2bc&amp;ie=UTF8&amp;ll=3.123922,101.737711&amp;spn=0,0&amp;t=m&amp;iwloc=0004ecaba7b1b9067e11d&amp;output=embed"
                height="400" width="415" frameborder="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>
            <small>View <a style="color: #0000ff; text-align: left;"
                           href="https://maps.google.com/maps/ms?msa=0&amp;msid=208986672469242443375.0004ecaba41bd6f35f2bc&amp;ie=UTF8&amp;ll=3.123922,101.737711&amp;spn=0,0&amp;t=m&amp;iwloc=0004ecaba7b1b9067e11d&amp;source=embed">Powerista
                    Industries Sdn Bhd</a> in a larger map
            </small>
        </div>
    </div>
    <div class="clear"></div>
</div>