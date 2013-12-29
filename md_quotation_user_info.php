<?php
$user_inst = get_userdata(get_post(get_the_ID())->post_author);
$user_meta_inst = get_user_meta(get_post(get_the_ID())->post_author);
?>
    <div class="wrap">

        <?php echo "<h2>User Details</h2>"; ?>

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
                <td><strong>Email Address</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>
                        <?php
                        if (isset($user_inst)) {

                            echo $user_inst->user_email;
                        } else {
                            echo ' - ';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Company Name</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>
                        <?php
                        if (isset($user_meta_inst['companyname'])) {
                            echo $user_meta_inst['companyname'][0];
                        } else {
                            echo ' - ';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Co/Registration No.</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>
                        <?php
                        if (isset($user_meta_inst['registration-no'])) {
                                echo $user_meta_inst['registration-no'][0];
                            } else {
                                echo ' - ';
                            }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Mobile No.</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>
                        <?php
                        if (isset($user_meta_inst['mobile'])) {
                            echo $user_meta_inst['mobile'][0];
                        } else {
                            echo ' - ';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Tel. No.</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>
                        <?php
                        if (isset($user_meta_inst['tel'])) {

                            echo $user_meta_inst['tel'][0];
                        } else {
                            echo ' - ';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Fax No.</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>

                        <?php
                        if (isset($user_meta_inst['fax'])) {

                            echo $user_meta_inst['fax'][0];
                        } else {

                            echo ' - ';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Address</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>
                        <?php

                        if(isset($user_meta_inst['address'])){

                            echo $user_meta_inst['address'][0];

                        }else{

                            echo ' - ';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Survey</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>
                        <?php
                        if(isset($user_meta_inst['survey'][0])){
                            $survey_meta =  unserialize($user_meta_inst['survey'][0]);
                        }
                        if(isset($survey_meta)){

                            foreach($survey_meta as $index => $value){
                                if($value == 'others'){
                                    echo ucfirst($value) . ': ' ;
                                }else{
                                    echo ucfirst($value);
                                }
                                if($index === count($survey_meta)-1){
                                    echo '<br/>';
                                }else{
                                    echo ', ';
                                }
                            }
                        }else{

                            echo ' - ';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Access Current Stock Price</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>
                        <?php
                        if(isset($user_meta_inst['view-pricing'])){

                            echo $user_meta_inst['view-pricing'][0];
                        }else{
                            echo '  - ';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>

    </div>
<?php
//}
