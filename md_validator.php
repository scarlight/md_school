<?php
// submit button name for signup only, for non-login user is Signup
function signup_validator(){

    $errors = array(
        'md-in-group' => array(),
        'md-in-companyname' => array(),
        'md-registration-no' => array(),
        'md-in-mobile' => array(),
        'md-in-telephone' => array(),
        'md-in-fax' => array(),
        'md-in-survey' => array(),
        'md-in-others-desc' => array(),
        'md-in-enquiry' => array(),
        'md-in-view-pricing' => array(),
        'md-in-sign' => array()
    );

    try {
            $refer = strstr($_SERVER['HTTP_REFERER'], '?' , true);
            if(!empty($refer)){
                $_SERVER['HTTP_REFERER'] = $refer;
            }

            $url_status_failed = add_query_arg(array(urlencode('status') => urlencode('signup')), $_SERVER['HTTP_REFERER']);


            if(empty($_POST['md-in-group'])){

                $errors['md-in-group'][] = 'Please register as agent or corporate';
            }else if($_POST['md-in-group'] != 'agent' and $_POST['md-in-group'] != 'corporate'){

                $errors['md-in-group'][] = 'Product quantity invalid. Enter product quantity in numbers';
            }

            if(empty($_POST['md-in-companyname'])){

                $_POST['md-in-companyname'] = sanitize_text_field($_POST['md-in-companyname']);
                $errors['md-in-companyname'][] = 'You must enter your company name';
            }else{
                if(strlen($_POST['md-in-companyname'] > 50)){

                    $errors['md-in-companyname'][] = 'Please make the company name field less than 50';
                }
            }

            if(isset($_POST['md-in-registration-no'])){

                $_POST['md-in-registration-no'] = sanitize_text_field($_POST['md-in-registration-no']);
                if(strlen($_POST['md-in-registration-no']) > 50){

                   $errors['md-in-registration-no'][] = 'Please make the company registration no. field less than 50';
                }
            }

            if(empty($_POST['md-in-mobile'])){

                $errors['md-in-mobile'][] = 'You must include your mobile number';
            }else{

                $_POST['md-in-mobile'] = sanitize_text_field($_POST['md-in-mobile']);
                $pattern = '/[0-9]{10, 13}/';
                if(preg_match($pattern, $_POST['md-in-mobile'], $match) && is_numeric($_POST['md-in-mobile'])){

                    $errors['md-in-mobile'][] = 'Invalid mobile number, please enter numbers between 10 and 13 numbers';
                }
            }

            if(isset($_POST['md-in-telephone']) ){

                $_POST['md-in-telephone'] = sanitize_text_field($_POST['md-in-telephone']);
                $pattern = '/[0-9]{10, 13}/';
                if(preg_match($pattern, $_POST['md-in-telephone'], $match) && is_numeric($_POST['md-in-telephone'])){

                    $errors['md-in-telephone'][] = 'Invalid telephone number, please enter numbers between 10 and 13 numbers';
                }
            }

            if(isset($_POST['md-in-fax'])){

                $_POST['md-in-fax'] = sanitize_text_field($_POST['md-in-fax']);
                $pattern = '/[0-9]{13}/';
                if(preg_match($pattern, $_POST['md-in-fax'], $match) && is_numeric($_POST['md-in-fax'])){

                    $errors['md-in-fax'][] = 'Invalid fax number, please enter numbers within 13 numbers';
                }
            }

            if(empty($_POST['md-in-address'])){

                $errors['md-in-address'][] = 'you must include your company address';
            }else{

                $_POST['md-in-address'] = sanitize_text_field($_POST['md-in-address']);
                if(strlen($_POST['md-in-address']) > 500){

                    $errors['md-in-address'][] = 'Company address must be within 500 characters';
                }
            }

            $survey_box = array('exhibition', 'search-engine', 'email-survey', 'friendfamily', 'advertisement', 'facebook', 'others');
            if(empty($_POST['md-in-survey'])){

                $errors['md-in-survey'][] = 'Please fill in the survey checkbox';
            }else if(!in_array('exhibition', $_POST['md-in-survey']) && !in_array('search-engine', $_POST['md-in-survey']) && !in_array('email-survey', $_POST['md-in-survey'])
                && !in_array('friendfamily', $_POST['md-in-survey']) && !in_array('advertisement', $_POST['md-in-survey']) && !in_array('facebook', $_POST['md-in-survey'])
                && !in_array('others', $_POST['md-in-survey'])){

                $errors['md-in-survey'][] = 'Please fill in the survey checkbox';
            }else{

                if(in_array('others', $_POST['md-in-survey'])){
                    if(empty($_POST['md-in-others-desc'])){

                        $errors['md-in-others-desc'][] = 'Please fill in the survey checkbox';
                    }else{

                        $_POST['md-in-others-desc'] = sanitize_text_field($_POST['md-in-others-desc']);
                        if(strlen($_POST['md-in-others-desc']) > 15){

                            $errors['md-in-others-desc'][] = 'Please fill in the survey checkbox, Others field in less than 15 characters';
                        }
                    }
                }
            }

            if (isset($_POST['md-in-enquiry'])) {

                $_POST['md-in-view-enquiry'] = sanitize_text_field($_POST['md-in-enquiry']);
                if(strlen($_POST['md-in-enquiry']) > 500){

                    $errors['md-in-enquiry'][] = 'You must include your enquiry within 500 characters';
                }
            }

            if(empty($_POST['md-in-view-pricing'])){

                $errors['md-in-view-pricing'][] = 'You moust include yes or no to view stock price';
            }else{

                $_POST['md-in-view-pricing'] = sanitize_text_field($_POST['md-in-view-pricing']);
                if($_POST['md-in-view-pricing'] != 'yes' and $_POST['md-in-view-pricing'] != 'no'){

                    $errors['md-in-view-pricing'][] =  'You moust include yes or no to view stock price';
                }
            }

    } catch (Exception $e) {
        $errors['md-in-sign'][] = 'An error has occured, please try again';
    }

    return $errors;
}

// submit button name for quote only, for login user is Send
function quote_validator(){

    $errors = array(
        'md-in' => array(),
        'md-in-quantity' => array(),
        'md-in-capacity' => array(),
        'md-in-product-color' => array(),
        'md-in-logo-front' => array(),
        'md-in-logo-front-color' => array(),
        'md-in-logo-back' => array(),
        'md-in-logo-back-color' => array(),
        'md-in-artwork' => array(),
        'md-in-accessories-type' => array(),
        'md-in-packaging-type' => array(),
        'md-in-print-packaging' => array(),
        'md-in-budget' => array(),
        'md-in-deadline' => array(),
        'md-in-other-requirement' => array()
    );

    try{

            if(isset($_POST['md-in-product']) && isset($_POST['__md__'])){

                if(is_numeric($_POST['__md__'])){
                    $post_product = get_post_custom($_POST['__md__']);
                    $product_model = $post_product['massdata_product_model'][0];

                    if($product_model != $_POST['md-in-product']){
                        $errors['md-in'][] = 'Product does not exist in the system. Please try a valid product.';
                    }
                }
            }

            if (empty($_POST['md-in-quantity'])) {

                $errors['md-in-quantity'][] = 'Product quantity is empty. Please try again';
            }else {

                $_POST['md-in-quantity'] = sanitize_text_field($_POST['md-in-quantity']);
                if (strlen($_POST['md-in-quantity']) <= 0 or strlen($_POST['md-in-quantity']) > 10) {

                    $errors['md-in-quantity'][] = 'Product quantity is overlimit. Please reduce limit and try again';
                }
                else if (!is_numeric($_POST['md-in-quantity'])){

                    $errors['md-in-quantity'][] = 'Product quantity invalid. Enter product quantity in numbers';
                }
                else if(is_float($_POST['md-in-quantity'])){

                    $errors['md-in-quantity'][] = 'Product quantity invalid. Enter product quantity in numbers';
                }
            }



            if (empty($_POST['md-in-capacity'])) {

                $errors['md-in-capacity'][] = 'Select product capacity. Please try again';
            } else {

                $_POST['md-in-capacity'] = sanitize_text_field($_POST['md-in-capacity']);
                $capacity = array('2', '4', '8', '16', '2000', '4000', '5000', '10000');
                if (!in_array($_POST['md-in-capacity'], $capacity)) {

                    $errors['md-in-capacity'][] = 'Select product capacity. Please try again';
                }
            }


            if(isset($_POST['md-in-product-color'])){

                $_POST['md-in-product-color'] = sanitize_text_field($_POST['md-in-product-color']);
                if(strlen($_POST['md-in-product-color']) > 30){

                    $errors['md-in-product-color'][] = 'Product color field is too long. Color field must be within 30 characters';
                }
            }

            if(empty($_POST['md-in-logo-front'])){

                $errors['md-in-logo-front'][] = 'Select front logo option';
            }else{

                $_POST['md-in-logo-front'] = sanitize_text_field($_POST['md-in-logo-front']);
                $logo_option = array('none', 'printing', 'laser-engraving', 'emboss');
                if(!in_array($_POST['md-in-logo-front'], $logo_option)){

                    $errors['md-in-logo-front'][] = 'Select front logo option';
                }
            }
            if(in_array($_POST['md-in-logo-front'], array('printing', 'laser-graving', 'emboss'))){
                if(empty($_POST['md-in-logo-front-color'])){

                    $errors['md-in-logo-front-color'][] = 'Please enter the number of colors for front';
                }else{

                    $_POST['md-in-logo-front-color'] = sanitize_text_field($_POST['md-in-logo-front-color']);
                    if($_POST['md-in-logo-front-color'] < 0 or $_POST['md-in-logo-front-color'] > 10){

                        $errors['md-in-logo-front-color'][] = 'Please enter the number of colors for front';
                    }
                    else if(!is_numeric($_POST['md-in-logo-front-color'])){

                        $errors['md-in-logo-front-color'][] = 'Please enter the number of colors for front';
                    }
                    else if(is_float($_POST['md-in-logo-front-color'])){

                        $errors['md-in-logo-front-color'][] = 'Please enter the number of colors for front';
                    }
                }
            }



            if(empty($_POST['md-in-logo-back'])){

                $errors['md-in-logo-back'][] = 'Select back logo option';
            }else{

                $_POST['md-in-logo-back'] = sanitize_text_field($_POST['md-in-logo-back']);
                $back_logo_option =  array('none', 'printing', 'laser-engraving', 'emboss');
                if(!in_array($_POST['md-in-logo-back'], $back_logo_option)){

                    $errors['md-in-logo-back'][] = 'Select back logo option';
                }
            }
            if(in_array($_POST['md-in-logo-back'], array('printing', 'laser-graving', 'emboss'))){
                if(empty($_POST['md-in-logo-back-color'])){

                    $errors['md-in-logo-back-color'][] = 'Please enter the number of colors for back';
                }else{

                    $_POST['md-in-logo-back-color'] = sanitize_text_field($_POST['md-in-logo-back-color']);
                    if ($_POST['md-in-logo-back-color'] < 1 or $_POST['md-in-logo-back-color'] > 10) {

                        $errors['md-in-logo-back-color'][] = 'Please enter the number of colors for back';
                    } else if (!is_numeric($_POST['md-in-logo-back-color'])) {

                        $errors['md-in-logo-back-color'][] = 'Please enter the number of colors for back';
                    } else if (is_float($_POST['md-in-logo-back-color'])) {

                        $errors['md-in-logo-back-color'][] = 'Please enter the number of colors for back';
                    }
                }
            }


           if(isset($_FILES['md-in-artwork']['error'])){

                $err_msg = $_FILES['md-in-artwork']['error'];
                if($err_msg == UPLOAD_ERR_INI_SIZE){
                    $errors['md-in-artwork'][] = 'Uploads file is too big. Maximum allowed is 20MB. Please try again';
                }else if($err_msg == UPLOAD_ERR_FORM_SIZE){
                    $errors['md-in-artwork'][] = 'The uploaded file exceeds max file upload. Please try again';
                }else if($err_msg == UPLOAD_ERR_PARTIAL){
                    $errors['md-in-artwork'][] = 'The uploaded file was interrupted. Please try again';
//                }else if($err_msg == UPLOAD_ERR_NO_FILE){
//                    $errors['md-in-artwork'][] = 'No file was uploaded. Please try again';
                }else if($err_msg == UPLOAD_ERR_NO_TMP_DIR){
                     $errors['md-in-artwork'][] = 'Failed to store your file. Please try again';
                }else if($err_msg == UPLOAD_ERR_CANT_WRITE){
                    $errros['md-in-artwork'][] = 'Failed to store your file to server. Please try again';
                }else if($err_msg == UPLOAD_ERR_EXTENSION){
                    $errors['md-in-artwork'][] = 'The file stopped uploading. Please try again';
                }
            }
            else if(isset($_FILES['md-in-artwork']['name'])){

                $supported_patterns = array('/(.pdf)$/', '/(.png)$/', '/(.jpg)$/', '/(.jpeg)$/', '/(.bmp)$/', '/(.ai)$/', '/(.psd)$/');
                $match = 0;

                $_FILES['md-in-artwork']['name'] = sanitize_file_name($_FILES['md-in-artwork']['name']);
                foreach($supported_patterns as $index => $pattern){

                    $match = preg_match($pattern, $_FILES['md-in-artwork']['name']);
                    if($match == 1) break;
                }
                if($match !== 1){
                    $errors['md-in-artwork'][] = 'The filetype of upload content is invalid. Accepted types are png, jpeg, bmp, ai, and psd';
                }
            }

            $accessory_type = array('none', 'lanyard', 'key-ring');
            if(empty($_POST['md-in-accessories-type'])){

                $errors['md-in-accessories-type'][] = 'Select an accessory';
            }else{

                $_POST['md-in-accessories-type'] = sanitize_text_field($_POST['md-in-accessories-type']);
                if(!in_array($_POST['md-in-accessories-type'], $accessory_type)){

                    $errors['md-in-accessories-type'][] = 'Select an accessory';
                }
            }

            $packaging_type = array('none', 'plastic-box', 'tin-box', 'mini-tin-box', 'white-paper-box', 'recycle-paper-box', 'mini-recycle-paper-box');
            if(empty($_POST['md-in-packaging-type'])){

                $errors['md-in-packaging-type'][] = 'Select a packaging';
            }else{


                if(!in_array($_POST['md-in-packaging-type'], $packaging_type)){

                    $errors['md-in-packaging-type'][] = 'Select a packaging';
                }
            }

            $packaging_print_type = array('none', 'printing', 'laser-engraving');
            if(isset($_POST['md-in-print-packaging'])){

                if (!in_array($_POST['md-in-print-packaging'], $packaging_print_type)) {

                    $errors['md-in-print-packaging'][] = 'Select a valid option for print of packaging';
                } else if (in_array($_POST['md-in-print-packaging'], array('printing', 'laser-engraving'))) {

                    if (empty($_POST['md-in-print-packaging-color'])) {

                        $errors['md-in-print-packaging-color'][] = 'Enter number of color for print of packaging';
                    } else {

                            $_POST['md-in-print-packaging-color'] = sanitize_text_field($_POST['md-in-print-packaging-color']);
                            if ($_POST['md-in-print-packaging-color'] < 1 or $_POST['md-in-print-packaging-color'] > 10) {

                                $errors['md-in-print-packaging-color'][] = "Enter number of color for print of packaging within 10";
                            } else if (!is_numeric($_POST['md-in-print-packaging-color']) or is_float($_POST['md-in-print-packaging-color'])) {

                                $errors['md-in-print-packaging-color'][] = 'Enter number of color for print of packaging';
                            }

                    }

                }
            }

            if(isset($_POST['md-in-budget'])){

                $_POST['md-in-budget'] = sanitize_text_field($_POST['md-in-budget']);
                if(strlen($_POST['md-in-budget']) > 30){

                    $errors['md-in-budget'][] = "Enter your budget within 30 characters";
                }
            }

            if(isset($_POST['md-in-deadline'])){
                $_POST['md-in-deadline'] = sanitize_text_field($_POST['md-in-deadline']);
            }
            if(isset($_POST['md-in-deadline']) && strlen($_POST['md-in-deadline']) > 1){

                $arr_date = (explode('/', $_POST['md-in-deadline']));

                if(count($arr_date) === 3){
                    $validate_number = array();
                    foreach($arr_date as $key => $value){

                        if(!is_numeric($value)){

                            $validate_number[$key] = 'no';
                        }else if(is_float($value) or $value < 1){

                            $validate_number[$key] = 'no';
                        }else{

                            $validate_number[$key] = 'yes';
                        }
                    }
                    if(in_array('no', $validate_number)){
                        $errors['md-in-deadline'][] = 'Invalid date format. Enter date for deadline';
                    }
                    else if(!checkdate($arr_date[1], $arr_date[0], $arr_date[2])){

                        $errors['md-in-deadline'][] = 'Invalid date format. Enter date for deadline';
                    }


                }

            }

            if(isset($_POST['md-in-other-requirement'])){

                $_POST['md-in-other-requirement'] = sanitize_text_field($_POST['md-in-other-requirement']);
                if(strlen($_POST['md-in-other-requirement']) > 500){
                    $errors['md-in-other-requirement'][] = 'Enter your other requirement within 500 characters';
                }
            }

    }catch(Exception $e){
        $errors['md-in'][] = 'An error has occured, please try again';
    }

    return array($errors);
}
