<?php

function send_admin_email_contact_us()
{
    remove_filter('wp_mail_content_type', 'set_html_content_type');
    add_filter('wp_mail', 'set_html_content_type');
    $headers = array('Content-type: text/html');
    add_filter('phpmailer_init', 'set_phpmailer_object');
    function set_html_content_type($params)
    {
        $params['headers'] = 'Content-type: text/html';
        return $params;
    }

    function set_phpmailer_object($phpmailer)
    {
        $phpmailer->IsHTML(true);
    }

    $html = <<<"CONTACT_US"
    <h3>A user has send enquiry from the contact form.</h3>
    <table>
        <thead>
        <tr>
            <td>Subject</td>
            <td>|</td>
            <td>Description</td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Full Name</td>
            <td>:</td>
            <td>{$_POST['user-login']}</td>
        </tr>
        <tr>
            <td>Email Address</td>
            <td>:</td>
            <td>{$_POST['user-email']}</td>
        </tr>
        <tr>
            <td>Company Name</td>
            <td>:</td>
            <td>{$_POST['md-in-companyname']}</td>
        </tr>
        <tr>
            <td>Co/Bis Reg No.</td>
            <td>:</td>
            <td>{$_POST['md-in-registration-no']}</td>
        </tr>
        <tr>
            <td>Mobile</td>
            <td>:</td>
            <td>{$_POST['md-in-mobile']}</td>
        </tr>
        <tr>
            <td>Tel</td>
            <td>:</td>
            <td>{$_POST['md-in-telephone']}</td>
        </tr>
        <tr>
            <td>Fax</td>
            <td>:</td>
            <td>{$_POST['md-in-fax']}</td>
        </tr>
        <tr>
            <td>Address</td>
            <td>:</td>
            <td>{$_POST['md-in-address']}</td>
        </tr>
        <tr>
            <td>Survey</td>
            <td>:</td>
            <td>
                <table>
                    <tbody>
CONTACT_US;

    $post_form = $_POST['md-in-survey'];
    foreach ($post_form as $index => $value) {

        switch ($value) {
            case 'exhibition':
                $html .= '<tr>';
                $html .= ($index + 1 . '. ' . 'Exhibition');
                $html .= '</tr>';
                break;
            case 'search-engine':

                $html .= '<tr>';
                $html .= ($index + 1 . '. ' . 'Search Engine');
                $html .= '</tr>';
                break;
            case 'email-survey':

                $html .= '<tr>';
                $html .= ($index + 1 . '. ' . 'Email');
                $html .= '</tr>';
                break;
            case 'friendfamily':

                $html .= '<tr>';
                $html .= ($index + 1 . '. ' . 'Friends & Families');
                $html .= '</tr>';
                break;
            case 'advertisement':

                $html .= '<tr>';
                $html .= ($index + 1 . '. ' . 'Advertisement');
                $html .= '</tr>';
                break;
            case 'facebook':

                $html .= '<tr>';
                $html .= ($index + 1 . '. ' . 'Facebook');
                $html .= '</tr>';
                break;
            case 'others':
                $_POST['md-in-others-desc'] = wp_strip_all_tags($_POST['md-in-others-desc']);
                $html .= '<tr>';
                $html .= ($index + 1 . '. ' . $_POST['md-in-others-desc']);
                $html .= '</tr>';
                break;
            default:
                break;
        }
    }

    $html2 = <<<"CONTACT_US"
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td>Enquiry</td>
            <td>:</td>
            <td>{$_POST['md-in-enquiry']}</td>
        </tr>
        </tbody>
    </table>
CONTACT_US;
    $html = $html.$html2;

    wp_mail(get_option('admin_email'), 'Massdata: Contact Us', $html);

    $message = 'Your enquiry has been send to the administrator';
    return $message;
}