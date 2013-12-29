<?php
$post_instance = get_post(get_the_ID());
$post_meta = get_post_meta( get_the_ID() );

?>
       <style>
           #postbox-container-1{
               display: none;
           }

           .add-new-h2{
               display: none;
           }
        </style>

        <script>
            var css = jQuery.noConflict();
            jQuery(document).ready(function () {
                jQuery('#title').attr('disabled', 'disabled');
            });
        </script>
    <div class="wrap">

        <?php
        //screen_icon();
        //echo "<h2>Quotation Information</h2>";
        ?><br>

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
                        <?php
                        if (isset($post_instance->post_author)) {
                            $user = get_userdata($post_instance->post_author);
                            echo 'Name: '.$user->data->user_login .' ; Role: ' . $user->roles[0];

                        } else {
                            echo ' - ';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Submitted date</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>
                        <?php
                        if (isset($post_instance)) {
                            echo $post_instance->post_date_gmt. ' GMT';
                        } else {
                            echo ' - ';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Product</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>
                        <?php
                        if (isset($product_instance)) {
                                echo $product_instance->post_content;
                            } else {
                                echo ' - ';
                            }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Quantity</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>
                        <?php
                        if (isset($post_meta)) {
                            echo $post_meta['md_in_quantity'][0];
                        } else {
                            echo ' - ';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Capacity</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>
                        <?php
                        if (isset($post_meta['md_in_capacity'])) {

                            switch ($post_meta['md_in_capacity'][0]) {

                                case '2':

                                    echo $post_meta['md_in_capacity'][0] . ' GB';
                                    break;
                                case '4':

                                    echo $post_meta['md_in_capacity'][0] . ' GB';
                                    break;
                                case '8':

                                    echo $post_meta['md_in_capacity'][0] . ' GB';
                                    break;
                                case '16':

                                    echo $post_meta['md_in_capacity'][0] . ' GB';
                                    break;
                                case'2000':

                                    echo $post_meta['md_in_capacity'][0] . ' +MAH';
                                    break;
                                case '4000':

                                    echo $post_meta['md_in_capacity'][0] . ' +MAH';
                                    break;
                                case '5000':

                                    echo $post_meta['md_in_capacity'][0] . ' +MAH';
                                    break;
                                case '10000':

                                    echo $post_meta['md_in_capacity'][0] . ' +MAH';
                                    break;
                            }
                        } else {
                            echo ' - ';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Product Color</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>

                        <?php
                        if (isset($post_meta['md_in_product_color'])) {

                            echo $post_meta['md_in_product_color'][0];
                        } else {

                            echo ' - ';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Logo option (Front)</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>
                        <?php

                        $front_temp= unserialize($post_meta['md_in_logo_front'][0]);

                        if(isset($front_temp[0])){

                            echo ucfirst($front_temp[0]);

                        }else{

                            echo ' - ';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>No. of Color (Front Logo)</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>
                        <?php
                        if(isset($front_temp[1])){

                            echo $front_temp[1];
                        }else{

                            echo '  - ';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Logo option (Back)</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>
                        <?php

                        $back_temp = unserialize($post_meta['md_in_logo_back'][0]);

                        if(isset($back_temp[0])){

                            echo ucfirst($back_temp[0]);
                        }else{

                            echo ' - ';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>No. of Color (Back Logo)</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>
                        <?php
                        if(isset($back_temp[1])){

                            echo $back_temp[1];
                        }else{

                            echo ' - ';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Design/ Artwork</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>
                        <?php

                        if(isset($post_meta['md_in_artwork'])){

                            $temp = unserialize($post_meta['md_in_artwork'][0]);
                            echo "<a href=\"{$temp['url']}\">Artwork Design file</a>";

                        }else{

                            echo ' - ';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Accessories</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>
                        <?php
                        if(isset($post_meta['md_in_accessories_type'])){

                            echo $post_meta['md_in_accessories_type'][0];
                        }else{

                            echo ' - ';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Packaging</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>
                        <?php
                        if(isset($post_meta['md_in_packaging_type'])){

                            echo $post_meta['md_in_packaging_type'][0];
                        }else{

                            echo ' - ';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Printing for Packaging</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>
                        <?php
                           if(isset($post_meta['md_in_print_packaging'])){

                              $temp = unserialize($post_meta['md_in_print_packaging'][0]);

                               if(isset($temp[0]) && $temp == 'none'){

                                   echo 'Print type: - ';
                               }else{

                                   echo 'Print type: - ';
                               }
                               echo '<br/>';
                               if(isset($temp[1]) && $temp[1] != 'none'){

                                   echo 'Number of colors: '.$temp[1];
                               }else{
                                   echo 'Number of colors: - ';
                               }
                        }else{

                            echo ' - ';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Budget (RM)</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>
                        <?php
                        if(isset($post_meta['md_in_budget'])){

                            echo $post_meta['md_in_budget'][0];
                        }else{

                            echo ' - ';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Deadline (DD MM YYYY)</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>
                        <?php
                        if(isset($post_meta['md_in_deadline'])){

                            echo $post_meta['md_in_deadline'][0];
                        }else{

                            echo ' - ';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Other requirement</strong></td>
                <td><strong>:</strong></td>
                <td>
                    <div>
                        <?php
                        if(isset($post_meta['md_in_other_requirement'])){

                            echo $post_meta['md_in_other_requirement'][0];
                        }else{

                            echo ' - ';
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
