<?php

function send_quote($product_id)
{
    $post_error = array();
    try{

        if(isset($_POST) && !empty($_POST)){


            if(empty($product_id)){

                throw new Exception('Quotation send for a non-existing product in the system. Possible attack on system!');
            }else{

                $post_product = get_post_custom($product_id);
                $product_model = $post_product['massdata_product_model'][0];

                $quote_information = array(
                    'post_title' => 'Model ' . $product_model,
                    'post_content' => $product_model,
                    'post_author' => get_current_user_id(),
                    'post_type' => 'massdata_quotation',
                    'post_status' => 'private'
                );

                $quote_id = wp_insert_post($quote_information);

                update_post_meta($quote_id, 'md_in_quantity', $_POST['md-in-quantity']);
                update_post_meta($quote_id, 'md_in_capacity', $_POST['md-in-capacity']);

                if (isset($_POST['md-in-product-color'])) {
                    $_POST['md-in-product-color'] = wp_strip_all_tags($_POST['md-in-product-color']);
                    update_post_meta($quote_id, 'md_in_product_color', $_POST['md-in-product-color']);
                }

                if ($_POST['md-in-logo-front'] == 'none') {
                    update_post_meta($quote_id, 'md_in_logo_front', array($_POST['md-in-logo-front']), true);
                } else {
                    $_POST['md-in-logo-front-color'] = wp_strip_all_tags($_POST['md-in-logo-front-color']);
                    update_post_meta($quote_id, 'md_in_logo_front', array($_POST['md-in-logo-front'], $_POST['md-in-logo-front-color']), true);
                }

                if ($_POST['md-in-logo-back'] == 'none') {
                    update_post_meta($quote_id, 'md_in_logo_back', array($_POST['md-in-logo-back']), true);
                } else {
                    $_POST['md-in-logo-back-color'] = wp_strip_all_tags($_POST['md-in-logo-back-color']);
                    update_post_meta($quote_id, 'md_in_logo_back', array($_POST['md-in-logo-back'], $_POST['md-in-logo-back-color']), true);
                }

                if (isset($_FILES['md-in-artwork']['size']) != 0 && isset($_FILES['md-in-artwork']['name']) && !empty($_FILES['md-in-artwork']['name'])) {

                    if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
//                    $_FILES['md-in-artwork']['name'] = md5(time()).$_FILES['md-in-artwork']['name'];
//                    $upload = wp_handle_upload($_FILES['md-in-artwork'], array('test_form' => false));

                    $upload = wp_upload_bits(md5(time()).$_FILES['md-in-artwork']['name'], null, file_get_contents($_FILES['md-in-artwork']['tmp_name']));

                    if (isset($upload['error']) && $upload['error'] != 0) {


                        if ( isset($upload['file']) && is_readable($upload['file'])) { //$upload['file'], $upload['url'], $upload['error']
                            $post_error[] = unlink($upload['file']);
                        } else {
                            $post_error[] = 'Failed to store design file. Please try to  upload again.'; // error to create upload folder
                        }
                    } else {
                        update_post_meta($quote_id, 'md_in_artwork', $upload, true);
                    }
                }

                update_post_meta($quote_id, 'md_in_accessories_type', $_POST['md-in-accessories-type']);
                update_post_meta($quote_id, 'md_in_packaging_type', $_POST['md-in-packaging-type']);

                // md-in-packaging-type, md-in-print-packaging, md-in-print-packaging-color
                if ($_POST['md-in-print-packaging'] == 'none') {

                    update_post_meta($quote_id, 'md_in_print_packaging', array($_POST['md-in-print-packaging']), true);
                } else {

                    $_POST['md-in-print-packaging-color'] = wp_strip_all_tags($_POST['md-in-print-packaging-color']);
                    update_post_meta($quote_id, 'md_in_print_packaging', array($_POST['md-in-print-packaging'], $_POST['md-in-print-packaging-color']), true);
                }

                if (isset($_POST['md-in-budget'])) {

                    $_POST['md-in-budget'] = wp_strip_all_tags($_POST['md-in-budget']);
                    update_post_meta($quote_id, 'md_in_budget', $_POST['md-in-budget']);
                }

                if (isset($_POST['md-in-deadline'])) {

                    $_POST['md-in-deadline'] = wp_strip_all_tags($_POST['md-in-deadline']);
                    update_post_meta($quote_id, 'md_in_deadline', $_POST['md-in-deadline']);
                }

                if (isset($_POST['md-in-other-requirement'])) {

                    $_POST['md-in-other-requirement'] = wp_strip_all_tags($_POST['md-in-other-requirement']);
                    update_post_meta($quote_id, 'md_in_other_requirement', $_POST['md-in-other-requirement']);
                }
                $email_user_id = (string)get_current_user_id();
                $email_product = (string)$product_model;
                $email_quote_id = (string)$quote_id;
                $email_message = <<<"HEREDOC"
A quotation was sent by a user:-
1. User ID: {$email_user_id}
2. Product Model: {$email_product}
3.Quote ID: {$email_quote_id}
HEREDOC;
                wp_mail(get_option('admin_email'), 'Masssdata System: Product Quotation', $email_message);
            }
        }

    }catch(Exception $ex){

        $post_error[] = 'Error in sending quote. Please try again later';
        return $post_error;
    }
    return $post_error;

}

function send_general_quote()
{
    $post_error = array();
    try{
        if(isset($_POST) && !empty($_POST)){

            $_POST['md-in-product-name'] = wp_strip_all_tags($_POST['md-in-product-name']);
                $quote_information = array(
                    'post_title' => 'General Quote: Model ' . $_POST['md-in-product-name'],
                    'post_content' => $_POST['md-in-product-name'],
                    'post_author' => get_current_user_id(),
                    'post_type' => 'massdata_quotation',
                    'post_status' => 'private'
                );

                $quote_id = wp_insert_post($quote_information);

                update_post_meta($quote_id, 'md_in_quantity', $_POST['md-in-quantity']);
                update_post_meta($quote_id, 'md_in_capacity', $_POST['md-in-capacity']);

                if (isset($_POST['md-in-product-color'])) {
                    $_POST['md-in-product-color'] = wp_strip_all_tags($_POST['md-in-product-color']);
                    update_post_meta($quote_id, 'md_in_product_color', $_POST['md-in-product-color']);
                }

                if ($_POST['md-in-logo-front'] == 'none') {
                    update_post_meta($quote_id, 'md_in_logo_front', array($_POST['md-in-logo-front']), true);
                } else {
                    $_POST['md-in-logo-front-color'] = wp_strip_all_tags($_POST['md-in-logo-front-color']);
                    update_post_meta($quote_id, 'md_in_logo_front', array($_POST['md-in-logo-front'], $_POST['md-in-logo-front-color']), true);
                }

                if ($_POST['md-in-logo-back'] == 'none') {
                    update_post_meta($quote_id, 'md_in_logo_back', array($_POST['md-in-logo-back']), true);
                } else {
                    $_POST['md-in-logo-back-color'] = wp_strip_all_tags($_POST['md-in-logo-back-color']);
                    update_post_meta($quote_id, 'md_in_logo_back', array($_POST['md-in-logo-back'], $_POST['md-in-logo-back-color']), true);
                }

                if (isset($_FILES['md-in-artwork']['size']) != 0 && isset($_FILES['md-in-artwork']['name']) && !empty($_FILES['md-in-artwork']['name'])) {

                if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
//                    $_FILES['md-in-artwork']['name'] = md5(time()).$_FILES['md-in-artwork']['name'];
//                    $upload = wp_handle_upload($_FILES['md-in-artwork'], array('test_form' => false));


                $upload = wp_upload_bits(md5(time()).$_FILES['md-in-artwork']['name'], null, file_get_contents($_FILES['md-in-artwork']['tmp_name']));

                if (isset($upload['error']) && $upload['error'] != 0) {


                    if ( isset($upload['file']) && is_readable($upload['file'])) { //$upload['file'], $upload['url'], $upload['error']
                        $post_error[] = unlink($upload['file']);
                    } else {
                        $post_error[] = 'Failed to store design file. Please try to  upload again.'; // error to create upload folder
                    }
                } else {
                    update_post_meta($quote_id, 'md_in_artwork', $upload, true);
                }

                }

                update_post_meta($quote_id, 'md_in_accessories_type', $_POST['md-in-accessories-type']);
                update_post_meta($quote_id, 'md_in_packaging_type', $_POST['md-in-packaging-type']);

                // md-in-packaging-type, md-in-print-packaging, md-in-print-packaging-color
                if ($_POST['md-in-print-packaging'] == 'none') {

                    update_post_meta($quote_id, 'md_in_print_packaging', array($_POST['md-in-print-packaging']), true);
                } else {

                    $_POST['md-in-print-packaging-color'] = wp_strip_all_tags($_POST['md-in-print-packaging-color']);
                    update_post_meta($quote_id, 'md_in_print_packaging', array($_POST['md-in-print-packaging'], $_POST['md-in-print-packaging-color']), true);
                }

                if (isset($_POST['md-in-budget'])) {

                    $_POST['md-in-budget'] = wp_strip_all_tags($_POST['md-in-budget']);
                    update_post_meta($quote_id, 'md_in_budget', $_POST['md-in-budget']);
                }

                if (isset($_POST['md-in-deadline'])) {

                    $_POST['md-in-deadline'] = wp_strip_all_tags($_POST['md-in-deadline']);
                    update_post_meta($quote_id, 'md_in_deadline', $_POST['md-in-deadline']);
                }

                if (isset($_POST['md-in-other-requirement'])) {

                    $_POST['md-in-other-requirement'] = wp_strip_all_tags($_POST['md-in-other-requirement']);
                    update_post_meta($quote_id, 'md_in_other_requirement', $_POST['md-in-other-requirement']);
                }

                $email_user_id = (string)get_current_user_id();
                $email_product = (string)$_POST['md-in-product-name'];
                $email_quote_id = (string)$quote_id;
                $email_message = <<<"HEREDOC"
A general quotation was sent by a user:-
1. User ID: {$email_user_id}
2. Product Model: {$email_product}
3. Quote ID: {$email_quote_id}
HEREDOC;
                wp_mail(get_option('admin_email'), 'Masssdata System: Product General Quotation', $email_message);
            }

    }catch(Exception $ex){

        $post_error[] = 'Error in sending quote. Please try again later';
        return $post_error;
    }
    return $post_error;

}

function send_user_data($user_id){

    $post_error = array();
    try{

        //if other is checked, ignore others
        $password= wp_generate_password(12, true);
        wp_update_user( array ('ID' => $user_id, 'role' => $_POST['md-in-group'] , 'user_pass' => $password) );
        update_user_meta($user_id, 'companyname', $_POST['md-in-companyname']);
        if(isset($_POST['md-in-registration-no'])){
            update_user_meta($user_id, 'registration_no', $_POST['md-in-registration-no']);
        }
        update_user_meta($user_id, 'mobile', $_POST['md-in-mobile']);
        if(isset($_POST['md-in-telephone'])){
            update_user_meta($user_id, 'tel', $_POST['md-in-telephone']);
        }
        if(isset($_POST['md-in-fax'])){
            update_user_meta($user_id, 'fax', $_POST['md-in-fax']);
        }
        update_user_meta($user_id, 'address', $_POST['md-in-address']);

        $survey_post = array();
        if(in_array('others', $_POST['md-in-survey']) && isset($_POST['md-in-others-desc'])){
            foreach($_POST['md-in-survey'] as $index => $value){

                if($value == 'others'){
                    $survey_post[$index] = $_POST['md-in-others-desc'];
                }else{
                    $survey_post[$index] = $value;
                }
            }
            update_user_meta($user_id, 'survey', $survey_post);
        }else{
            update_user_meta($user_id, 'survey', $_POST['md-in-survey']);
        }

        if(isset($_POST['md-in-enquiry'])){
            update_user_meta($user_id, 'enquiry', $_POST['md-in-enquiry']);

$user_data = get_userdata($user_id);
$username = $user_data->data->user_login;
$enquiry = $_POST['md-in-enquiry'];
$user_enquiry = <<<"HERE"
A new user has send enquiry when the user registered:
Username: {$username}
User ID: {$user_id}

Enquiry:
{$enquiry}
HERE;
            wp_mail(get_option('admin_email'), 'Massdata System: Enquiry during User Registration', $user_enquiry);
        }


        update_user_meta($user_id, 'view_pricing', $_POST['md-in-view-pricing']);

        //get current user id and his user login and password
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);

        $user_obj = get_userdata($user_id);
        $user_email = $user_obj->data->user_email;
        $user_password = $password;
        $site = site_url();
        $email_message = <<<"User_register"
Thank you for signing up to Massdata.

Your login is your email  : {$user_email}
Your password                : {$password}
You can now login into Massdata, {$site}
User_register;
        wp_mail($user_email, 'Massdata System: User Registration Email', $email_message);

        $email_user_id = (string)get_current_user_id();
        $email_username = (string)$_POST['user_login'];
        $email_role = (string)$_POST['md-in-group'];
        $email_address = (string)$_POST['md-in-address'];
        $email_companyname = (string)$_POST['md-in-companyname'];
        $email_mobile = (string)$_POST['md-in-mobile'];
        $email_message = <<<"HEREDOC"

A new user has registered into the Massdata System:

 User ID : {$email_user_id}
 Username : {$email_username}
 Role : {$email_role}
 Mobile no. : {$email_mobile}
 Company name : {$email_companyname}
 Address : {$email_address}
HEREDOC;
        wp_mail(get_option('admin_email'), 'Massdata System: New User Registration Notification', $email_message);

    }catch(Exception $ex){

        $post_error[] = 'Error in registering user. Please try again later';
        return $post_error;
    }
    return $post_error;
}
