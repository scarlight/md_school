<?php

?>
<div id="login">
    <?php if(is_user_logged_in()) { ?>
        <ul>
            <li><a class="signout" href="<?php echo wp_logout_url( site_url() ); ?>">Sign Out,</a></li>
            <li>
                <strong>
                <span class="orange-text">Welcome! &nbsp;&nbsp;</span><?php $a = get_userdata(get_current_user_id()); if($a) echo $a->data->display_name ; else  echo $a->data->user_login;?>
                </strong>
            </li>
        </ul>
    <?php }else { ?>
        <ul>
            <li><a class="login" href="#">Login</a></li>
            <li><a class="signup" href="#">Sign Up</a></li>
        </ul>
    <?php } ?>
    <div class="clear"></div>
    <?php get_search_form(); ?>

<?php
$status = null;
$message = null;
    if(isset($_GET['status']) && $_GET['status'] === 'signin'){
        $status = 'signin';
        $message = urldecode($_GET['message']);
    }else if(isset($_GET['status']) && $_GET['status'] === 'signup'){
        $status = 'signup';
        $message = urldecode($_GET['message']);
    }
?>
<div id="login-modal" class="modal fade" tabindex="-1">
    <button type="button" class="login-modal-close">&times;</button>
    <div class="modal-body">
        <h2>Login</h2>
        <form action="<?php bloginfo('url') ?>/wp-login.php" method="post">
            <div class="form-group">

                <?php
                if ($status == 'signin') {
                    echo "<p id='md_signin_err' style='color:red; font-style: italic'><strong>{$message}</strong></p>";
                }
                ?>
                <label class="control-label" for="login-email">Full Name/Username</label>
                <input type="text" id="login-email" name="log" class="form-control login-email" value="<?php if (isset($_POST['log'])) echo esc_attr($_POST['log']); ?>">
            </div>
            <div class="form-group">
                <label class="control-label" for="login-password">Password</label>
                <input type="password" id="login-password" name="pwd" class="form-control login-password" value="<?php if (isset($_POST['pwd'])) echo esc_attr($_POST['pwd']); ?>">
            </div>
            <div class="form-group" style="margin-top:20px;">
                <?php do_action('login_form'); ?>
                <input type="submit" id="login-btn" name="wp-submit" class="btn btn-dafault" value="Login">
            </div>
        </form>
        <br>
        <a class="blue-text-signup" href=""><i>Not a member yet? Sign up Now!</i></a>
        <div class="clear"></div>
    </div>
</div>

<div id="signup-modal" class="modal fade" tabindex="-1">
    <button type="button" class="signup-modal-close">&times;</button>
    <div class="modal-body">
        <h2>Sign Up</h2>
            <form action="<?php echo site_url('wp-login.php?action=register', 'login_post'); ?>" method="post">

                <?php
                if ($status === 'signup') {
                    echo "<p id='md_signup_err' style='color:red; font-style: italic'><strong>{$message}</strong></p>";
                }
                ?>
                <div class="form-group">
                <label for="signup-name" class="control-label">Full Name <span class="required">*</span></label>
                <input type="text" class="form-control" id="signup-name" name="user_login" placeholder="First and Last Name" value="<?php if (isset($_POST['user_login'])) echo esc_attr($_POST['user_login']); ?>" required>
            </div>
            <div class="form-group">
                <label for="signup-email" class="control-label">Email Address <span class="required">*</span></label>
                <input type="email" class="form-control" id="signup-email" name="user_email" placeholder="abc@gmail.com" value="<?php if (isset($_POST['user_email'])) echo esc_attr($_POST['user_email']); ?>" required>
            </div>
            <div class="form-group classification">
                <input type="radio" id="signup-agent" name="md-in-group" value="agent">
                <label for="signup-agent" class="control-label aligntop">Agent</label>
                <input type="radio" id="signup-corporate" name="md-in-group" value="corporate">
                <label for="signup-corporate" class="control-label aligntop">Corporate</label>
            </div>
            <div class="form-group">
                <label for="signup-companyname" class="control-label">Company name <span class="required">*</span></label>
                <input type="text" class="form-control" id="signup-companyname" name="md-in-companyname" placeholder="Name of Your Company" value="<?php if (isset($_POST['log'])) echo esc_attr($_POST['md-in-companyname']); ?>" required>
            </div>
            <div class="form-group">
                <label for="signup-registration-no" class="control-label">Co / Bis Reg. no.</label>
                <input type="text" class="form-control" id="signup-registration-no" name="md-in-registration-no" value="<?php if (isset($_POST['md-in-registration-no'])) echo esc_attr($_POST['md-in-registration-no']); ?>">
            </div>
            <div class="form-group">
                <label for="signup-mobile" class="control-label">Mobile <span class="required">*</span></label>
                <input type="text" class="form-control" id="signup-mobile" name="md-in-mobile" placeholder="e.g. 601X XXXXXXX" value="<?php if (isset($_POST['md-in-mobile'])) echo esc_attr($_POST['md-in-mobile']); ?>" required>
            </div>
            <div class="form-group">
                <label for="signup-tel" class="control-label">Tel</label>
                <input type="text" class="form-control" id="signup-tel" name="md-in-telephone" placeholder="e.g. 601X XXXXXXX" value="<?php if (isset($_POST['md-in-telephone'])) echo esc_attr($_POST['md-in-telephone']); else echo ""; ?>">
            </div>
            <div class="form-group">
                <label for="signup-fax" class="control-label">Fax</label>
                <input type="text" class="form-control" id="signup-fax" name="md-in-fax" placeholder="e.g. 601X XXXXXXX" value="<?php if (isset($_POST['md-in-fax'])) echo esc_attr($_POST['md-in-fax']); ?>">
            </div>
            <div class="form-group">
                <label for="signup-address" class="control-label aligntop">Address <span class="required">*</span></label>
                <textarea class="form-control textarea" name="md-in-address" id="signup-address" cols="30" rows="4" value="<?php if (isset($_POST['md-in-address'])) echo esc_attr($_POST['md-in-address']); ?>" required></textarea>
            </div>

            <div class="form-group">
                <label for="" class="control-label aligntop">Survey <span class="required">*</span></label>
                
                <input type="checkbox" id="signup-exhibition" name="md-in-survey[]" value="exhibition">
                <label for="signup-exhibition" class="control-label aligntop">Exhibition</label>
                
                <input type="checkbox" id="signup-search-engine" name="md-in-survey[]" value="search-engine">
                <label for="signup-search-engine" class="control-label aligntop">Search Engine</label>

                <div class="classification">
                    <input type="checkbox" id="signup-email-survey" name="md-in-survey[]" value="email-survey">
                    <label for="signup-email-survey" class="control-label aligntop">Email</label>
                    
                    <input type="checkbox" id="signup-friendfamily" name="md-in-survey[]" value="friendfamily">
                    <label for="signup-friendfamily" class="control-label aligntop">Friends &amp; Families</label>
                </div>

                <div class="classification">
                    <input type="checkbox" id="signup-advertisement" name="md-in-survey[]" value="advertisement">
                    <label for="signup-advertisement" class="control-label aligntop">Advertisement</label>
                    
                    <input type="checkbox" id="signup-facebook" name="md-in-survey[]" value="facebook">
                    <label for="signup-facebook" class="control-label aligntop">Facebook</label>
                </div>

                <div class="classification">
                    <input type="checkbox" id="signup-others" name="md-in-survey[]" value="others">
                    <label for="signup-others" class="control-label aligntop" style="width:50px;">Others</label>
                    <input type="text" class="form-control floatr" id="signup-others-desc" name="md-in-others-desc" value="<?php if (isset($_POST['md-in-others-desc'])) echo esc_attr($_POST['md-in-others-desc']); ?>" style="width:220px; margin-right:2px;">
                    <div class="clear"></div>
                </div>

            </div>

                <div class="form-group">
                    <label for="" class="control-label aligntop">Register to access Current Stock <br>Pricing <span
                            class="required">*</span></label>

                    <input type="radio" id="yes-pricing" name="md-in-view-pricing" value="yes" checked>
                    <label for="yes-pricing" class="control-label aligntop">Yes</label>
                    <input type="radio" id="no-pricing" name="md-in-view-pricing" value="no">
                    <label for="no-pricing" class="control-label aligntop">No</label>
                </div>
            <br>

            <div class="form-group">
                <hr class="stiched">
                <p class="floatl" style="margin-top:30px; font-style:italic;">
                    "<span class="required" style="font-style:normal;">*</span>" is the mandatory field
                </p>
                <br>
                <?php do_action('register_form') ?>
                <input type="reset" class="floatr btn btn-default" value="Reset">
                <input type="submit" name="md-signup" class="floatr btn btn-default" value="Submit">
                <div class="clear"></div>
            </div>
        </form>
    </div>
</div>

</div>