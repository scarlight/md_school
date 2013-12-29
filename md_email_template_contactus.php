<?php

function massdata_contactus_template()
{
    remove_filter('wp_mail_content_type', 'set_html_content_type');
    add_filter('wp_mail_content_type', 'set_html_content_type');
    function set_html_content_type()
    {
        return 'text/html';
    }

    ob_start();
    ?>
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
            <td><?php echo $_POST['user-login']; ?></td>
        </tr>
        <tr>
            <td>Email Address</td>
            <td>:</td>
            <td><?php echo $_POST['user-email']; ?></td>
        </tr>
        <tr>
            <td>Company Name</td>
            <td>:</td>
            <td><?php echo $_POST['md-in-companyname']; ?></td>
        </tr>
        <tr>
            <td>Co/Bis Reg No.</td>
            <td>:</td>
            <td><?php echo $_POST['md-in-registration-no']; ?></td>
        </tr>
        <tr>
            <td>Mobile</td>
            <td>:</td>
            <td><?php echo $_POST['md-in-mobile']; ?></td>
        </tr>
        <tr>
            <td>Tel</td>
            <td>:</td>
            <td><?php echo $_POST['md-in-tel']; ?></td>
        </tr>
        <tr>
            <td>Fax</td>
            <td>:</td>
            <td><?php echo $_POST['md-in-fax']; ?></td>
        </tr>
        <tr>
            <td>Address</td>
            <td>:</td>
            <td><?php echo $_POST['md-in-address']; ?></td>
        </tr>
        <tr>
            <td>Survey</td>
            <td>:</td>
            <td>
                <table>
                    <tbody>
                    <?php
                    $post_form = $_POST['md-in-survey'];
                    foreach ($post_form as $index => $value) {

                        switch ($value) {
                            case 'exhibition':
                                echo '<tr>';
                                echo $index + 1 . '. ' . 'Exhibition';
                                echo '</tr>';
                                break;
                            case 'search-engine':

                                echo '<tr>';
                                echo $index + 1 . '. ' . 'Search Engine';
                                echo '</tr>';
                                break;
                            case 'email-survey':

                                echo '<tr>';
                                echo $index + 1 . '. ' . 'Email';
                                echo '</tr>';
                                break;
                            case 'friendfamily':

                                echo '<tr>';
                                echo $index + 1 . '. ' . 'Friends & Families';
                                echo '</tr>';
                                break;
                            case 'advertisement':

                                echo '<tr>';
                                echo $index + 1 . '. ' . 'Advertisement';
                                echo '</tr>';
                                break;
                            case 'facebook':

                                echo '<tr>';
                                echo $index + 1 . '. ' . 'Facebook';
                                echo '</tr>';
                                break;
                            case 'others':
                                $_POST['md-in-other-desc'] = wp_strip_all_tags($_POST['md-in-other-desc']);
                                echo '<tr>';
                                echo $index + 1 . '. ' . $_POST['md-in-other-desc'];
                                echo '</tr>';
                                break;
                            default:
                                break;
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td>Enquiry</td>
            <td>:</td>
            <td><?php echo $_POST['md-in-enquiry']; ?></td>
        </tr>
        </tbody>
    </table>
<?php
    $a = ob_get_contents();
    return $a;
}

