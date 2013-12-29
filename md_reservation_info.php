<?php

global $post;

$post_instance = get_post(get_the_ID());
$post_meta = get_post_meta(get_the_ID());
$user_inst = get_userdata($post->post_author);
$user_meta_inst = get_user_meta($post->author);

$query = new WP_Query(
    array(
        'post_type' => 'massdata_reserve',
        'post_content' => $post->ID,
        'fields' => 'ids'
    )
);
echo '<pre>';
$bigarr = array();
foreach($query->posts as $index => $ids){

    //denormlaize first
   $temp = array();
   $a = get_post_meta($query->posts[$index]);
   unset($a['_edit_lock']);

    foreach($a as $ids => $stock_count){
        $temp[$ids] = $stock_count[0];
   }
    $bigarr[] = $temp;
}

$reserved_stock_counts = array();
foreach($bigarr as $index => $array){
    foreach($array as $id => $reserve_count){
        if(!isset($reserved_stock_counts[$id])){
            $reserved_stock_counts[$id] = 0;
            $reserved_stock_counts[$id] += $reserve_count;
        }else{
            $reserved_stock_counts[$id] += $reserve_count;
        }
    }
}
echo '</pre>';
//parent =  409
// childredn(12) = 508, 498, 500, 501, 499, 502, 505, 509, 504, 503, 506, 507
// post_parent value of 409
?>
    <style>
        #postbox-container-1 {
            display: none;
        }

        .add-new-h2{
            display: none;
        }

        input#title{

        }
    </style>
    <script>
        var css = jQuery.noConflict();
        jQuery(document).ready(function(){
            jQuery('#title').attr('disabled','disabled');
        });

    </script>

    <div class="wrap">

        <table class="widefat fixed">
            <tbody>
            <tr>
                <td style="width:200px;">Product ID</td>
                <td>345</td>
            </tr>
            <tr>
                <td>Product Name</td>
                <td>FD-SUPER COLOR</td>
            </tr>
            </tbody>
        </table>
        <br/>
        <table class="widefat fixed">
            <thead>
            <tr>
                <th class="manage-column">Product Color</th>
                <th class="manage-column">Reserved</th>
                <th class="manage-column">Total Stock</th>
                <th class="manage-column">Available Stock</th>
            </tr>
            </thead>
            <tbody>
            <?php

            $posts = get_post_meta($post->ID);
            unset($posts['_edit_lock']);
            $total_reserved = 0;
            $total_stock = 0;
            $total_available_stock = 0;

            foreach ($posts as $i => $reserved) {
                // get available  stock, minus all reservation stock from all reserved.

                // get reserved stock, and total stock
                $color = get_post_meta($i, 'attribute_pa_color', true);
                $stock = get_post_meta($i, '_stock', true);

                $color_temp = explode('-', $color);
                if (!empty($color_temp)) {
                    foreach ($color_temp as $j => $color_name) {
                        $color_temp[$j] = ucfirst($color_name);
                    }
                    $color = implode(' ', $color_temp);
                }else {
                    $color = ucfirst($color);
                }

                echo '<tr>';
                echo "<td>{$color}</td>";
                echo "<td>{$reserved[0]}</td>";
                echo "<td>{$stock}</td>";
                $available_stock = $stock - $reserved_stock_counts[$i];
                echo "<td>{$available_stock}</td>";
                echo '</tr>';

                $total_reserved = $total_reserved + $reserved[0];
                $total_stock = $total_stock + $stock;
                $total_available_stock = $total_available_stock + $available_stock;
            }

            ?>
            <tr class="total-stock-count">
                <td>Total</td>
                <td><?php echo "{$total_reserved}"; ?></td>
                <td><?php echo "{$total_stock}"; ?></td>
                <td><?php echo "{$total_available_stock}"; ?></td>
            </tr>
            </tbody>
        </table>

        <br/>
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
                <td><strong>Username</strong></td>
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